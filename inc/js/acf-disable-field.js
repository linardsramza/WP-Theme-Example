( function( wp ) {
    window.document.addEventListener(
        'DOMContentLoaded',
        () => {
            setTimeout(() => {
                const postType = wp.data.select('core/editor').getCurrentPostType();
                if(postType !== 'workshops' && postType !== 'cities') {
                    acf.addAction('append_field/name=exclude_block_from_content_update', function($field){
                        $field.$el.hide();
                    });
                }
            }, 100);
        }
    );
} )( window.wp );
