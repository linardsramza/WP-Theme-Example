/* globals Choices, gform */

'use strict';

const addWorkshopSelectStyling = () => {
    const allWorkshopSelectFields = document.querySelectorAll('.select-workshop select');
    let searchFormPlaceHolder = document.querySelector('.form__search-field-placeholder');
    searchFormPlaceHolder = searchFormPlaceHolder.innerHTML;
    allWorkshopSelectFields.forEach(select => {
        new Choices(select, {
            searchEnabled: true,
            searchPlaceholderValue: searchFormPlaceHolder,
            itemSelectText: '',
            shouldSort: false
        });
    });
}

jQuery(document).on('gform_page_loaded', function(event, form_id, current_page){
    addWorkshopSelectStyling();
});

addWorkshopSelectStyling();
