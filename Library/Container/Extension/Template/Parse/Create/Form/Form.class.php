<?php declare(strict_types=1);

/**
 * Template Parse Create Form
 *
 * Create a Form
 *
 * @modul author Stefan Schlombs
 * @modul version 1.0.0
 * @modul versionRequiredSystem 1.0.0
 * @modul hasCSS default|switch|grouprow
 *
 */
class ContainerExtensionTemplateParseCreateForm extends ContainerExtensionTemplateParseCreate_abstract
{

    /**
     *
     * Parse the Template.
     *
     * @return string
     * @throws DetailedException
     */
    function parse(): string
    {
        $parameter = $this->getParameter();

        if (isset($parameter['get'])) {
            if ($parameter['get'] === 'label') {
                if ($parameter['name'] !== null) {
                    return ContainerExtensionTemplateParseCreateFormRequest::getLabel($parameter['form'] . '_' . $parameter['name']);
                }
                else {
                    throw new DetailedException('missingName',
                                                0,
                                                null,
                                                [
                                                    'debug' => [
                                                        $parameter
                                                    ]
                                                ]);
                }
            }
            elseif ($parameter['get'] === 'info') {
                return ContainerExtensionTemplateParseCreateFormRequest::getInfo($parameter['form'] . '_' . $parameter['name']);
            }
            elseif ($parameter['get'] === 'error') {
                return ContainerExtensionTemplateParseCreateFormRequest::getError($parameter['form'] . '_' . $parameter['name']);
            }
        }
        else {

            if ($parameter['name'] !== null) {
                return ContainerExtensionTemplateParseCreateFormRequest::getElement($parameter['form'] . '_' . $parameter['name']);
            }
            else {
                throw new DetailedException('missingName',
                                            0,
                                            null,
                                            [
                                                'debug' => [
                                                    $parameter
                                                ]
                                            ]);
            }
        }

        return '';
    }
}

