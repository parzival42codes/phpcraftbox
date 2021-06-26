<?php

class CoreClassesHelperDatetime
{

    protected static object   $languageCache;
    protected static DateTime $dateTimeCache;
    protected static array    $systemlanguageFormat = [];

    public static function view(string $dateTime, bool $named = true): ?string
    {

        self::init();

        if ($dateTime === null || $dateTime === 0) {
            if ($named === false) {
                return '';
            }
            else {
                return self::$languageCache->get('/named/never');
            }
        }
        elseif ($dateTime === '0000-00-00 00:00:00') {
            if ($named === false) {
                return '';
            }
            else {
                return self::$languageCache->get('/named/ever');
            }
        }

        $exceptioncatch = Container::get('Exceptioncatch',
            function ($dateTime) {
                return new \DateTime($dateTime);
            },
                                         $dateTime);

        if ($exceptioncatch->hasException() === true) {
            \CoreErrorhandler::trigger(__METHOD__,
                                       'wrongDateTime',
                                       ['dateTime' => $dateTime]);
            return null;
        }


        $dateTimeObject = $exceptioncatch->getReturn();

        if ($named === false) {
            return $dateTimeObject->format(\Config::get('/cms/date/day') . ' ' . $dateTimeObject->format(\Config::get('//core/cms/date/time')));
        }
        else {
            $dateTimeDiff = self::$dateTimeCache->diff($dateTimeObject);

            /** @var ContainerExtensionTemplate $dateTimeNamed */
            $dateTimeNamed = Container::get('ContainerExtensionTemplate');
            $dateTimeNamed->set(self::$systemlanguageFormat[strtoupper(\Config::get('/environment/config/iso_language_code'))]['named']);
            $dateTimeNamed->assign('date',
                                   $dateTimeObject->format(self::$systemlanguageFormat[strtoupper(\Config::get('/environment/config/iso_language_code'))]['date']));
            $dateTimeNamed->assign('time',
                                   $dateTimeObject->format(self::$systemlanguageFormat[strtoupper(\Config::get('/environment/config/iso_language_code'))]['time']));

            $dateTimeNamed->assign('namedAt',
                                   '');
            if ($dateTimeObject->format(self::$systemlanguageFormat[strtoupper(\Config::get('/environment/config/iso_language_code'))]['date']) === self::$dateTimeCache->format(self::$systemlanguageFormat[strtoupper(\Config::get('/environment/config/iso_language_code'))]['date'])) {
                $dateTimeNamed->assign('namedAt',
                                       self::$languageCache->get('/named/daytime/today'));
            }
            if ($dateTimeDiff->m == 1) {
                $dateTimeNamed->assign('namedAt',
                                       self::$languageCache->get('/named/daytime/atLastMonth'));
            }
            elseif ($dateTimeDiff->d == 1) {
                $dateTimeNamed->assign('namedAt',
                                       self::$languageCache->get('/named/daytime/yesterday'));
            }

            $dateTimeNamed->parse();

            return $dateTimeNamed->get();
        }
    }

    protected static function init(): void
    {
        if (!self::$languageCache) {
            self::$languageCache = Container::get('ContainerExtensionLanguage',
                                                  Core::getRootClass(__CLASS__),
                                                  'default');
        }
    }

    public static function calculateDifferenceSecounds(int $secounds, bool $showEmpty = false): string
    {
        $dateStart = new \DateTime();
        $dateEnd   = clone $dateStart;
        $dateEnd->modify('+' . $secounds . ' sec');
        return self::calculateDifference($dateStart,
                                         $dateEnd,
                                         $showEmpty);
    }

    public static function calculateDifference(DateTime|string $dateStart, DateTime|string $dateEnd, bool $showEmpty = false): string
    {

        self::init();

        if (!is_object($dateStart)) {
            $dateStart = new \DateTime($dateStart);
        }

        if (!is_object($dateEnd)) {
            $dateEnd = new \DateTime($dateEnd);
        }

        $dateDiff = $dateStart->diff($dateEnd);

        $output = '';

        $dateArray = [
            'y',
            'm',
            'd',
            'h',
            'i',
            's'
        ];

        foreach ($dateArray as $dateArrayValue) {
            if ($dateDiff->$dateArrayValue > 0 || $showEmpty === true) {
                $output .= ' ' . $dateDiff->$dateArrayValue . ' ' . self::$languageCache->get('/difference/' . $dateArrayValue) . PHP_EOL;
            }
        }

        return $output;
    }

}
