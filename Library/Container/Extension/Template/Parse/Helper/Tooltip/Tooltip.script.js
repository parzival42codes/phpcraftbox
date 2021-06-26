$(document).ready(function () {

    $('.ContainerExtensionTemplateParseHelperTooltip_hover').on("mouseenter", function () {
        let $this = $(this);

        let item = $('#ContainerExtensionTemplateParseHelperTooltip_container_item' + $this.data('tooltip'));

        if (!item.hasClass('ContainerExtensionTemplateParseHelperTooltip_container_item_calc')) {
            let $offsetTop = $this.offset().top;
            let $offsetLeft = $this.offset().left;
            let offsetCalc = $offsetTop - item.outerHeight() - $this.outerHeight();
            item.offset({top: offsetCalc, left: $offsetLeft});
        }

        item.addClass('ContainerExtensionTemplateParseHelperTooltip_container_item_calc');
        item.show();
        // console.log('mouseenter', item, $this, $this.offset(), $offsetTop - 50 - item.outerHeight());
    }).on("mouseleave", function () {
        let $this = $(this);
        let item = $('#ContainerExtensionTemplateParseHelperTooltip_container_item' + $this.data('tooltip'));
        item.hide();
        // console.log('mouseleave', item, $this, $this.offset());
    });

});
