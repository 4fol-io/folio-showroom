/**
 * FolioShowroom Editor Post/Page Main React Script
 *
 * Adds Showroom custom panels to WP Block Editor Sidebar
 *
 * @package Folio
 */


import FolioTextControl from './components/FolioTextControl.js';
import FolioToggleControl from './components/FolioToggleControl.js';
import FolioImageControl from './components/FolioImageControl.js';
import FolioSelectControl from './components/FolioSelectControl.js';

(function (wp) {

    const { registerPlugin } = wp.plugins;
    const { PluginDocumentSettingPanel } = wp.editPost;
    const { withSelect } = wp.data;
    const { __ } = wp.i18n;

    const ShowRoomPanel = ({ template }) => {

        /*
        if( 'template.php' !== template ) {
            return null
        }
        return template;
        */

        return (
            <>
                <PluginDocumentSettingPanel
                    name="folio-showroom-settins-panel"
                    title={__('Showroom Settings', 'folio-showroom')}
                >
                    <FolioToggleControl
                        label={__('Featured?', 'folio-showroom')}
                        metaKey="_folio_showroom_featured"
                        help={__('Featured posts are displayed in the home slider.', 'folio-showroom')}
                    />

                    <FolioSelectControl
                        label={__('Header style', 'folio-showroom')}
                        metaKey="_folio_showroom_header_theme"
                        options={[
                            { value: '', label: __('Default', 'folio-showroom') },
                            { value: 'light', label: __('Light', 'folio-showroom') },
                            { value: 'dark', label: __('Dark', 'folio-showroom') },
                            { value: 'transparent', label: __('Transparent Default', 'folio-showroom') },
                            { value: 'transparent-light', label: __('Transparent Light', 'folio-showroom') },
                            { value: 'transparent-dark', label: __('Transparent Dark', 'folio-showroom') },
                        ]}>
                    </FolioSelectControl>

                    <FolioToggleControl
                        label={__('Header overlay?', 'folio-showroom')}
                        metaKey="_folio_showroom_header_overlay"
                        help={__('Removes header margin for fullwidth sliders, covers, etc.', 'folio-showroom')}
                    />

                    <FolioToggleControl
                        label={__('Hide title?', 'folio-showroom')}
                        metaKey="_folio_showroom_hide_title"
                        bodyClassOn="showroom-hide-title"
                    />

                    <FolioToggleControl
                        label={__('Hide header info?', 'folio-showroom')}
                        metaKey="_folio_showroom_hide_meta"
                    />

                    <FolioToggleControl
                        label={__('Hide featured image?', 'folio-showroom')}
                        metaKey="_folio_showroom_hide_featured_img"
                    />

                </PluginDocumentSettingPanel>
                <PluginDocumentSettingPanel
                    name="folio-showroom-author-panel"
                    title={__('Showroom author', 'folio-showroom')}
                >
                    <FolioImageControl
                        metaKey="_folio_showroom_author_avatar"
                        label={__('Author image', 'folio-showroom')}
                        addLabel={__('Upload image', 'folio-showroom')}
                        removeLabel={__('Remove image', 'folio-showroom')}
                        className={"folio-author-avatar"}
                    />
                    <FolioTextControl
                        label={__('Author name', 'folio-showroom')}
                        metaKey="_folio_showroom_author_fullname"
                    />
                    <FolioTextControl
                        label={__('Author url', 'folio-showroom')}
                        metaKey="_folio_showroom_author_url"
                    />
                </PluginDocumentSettingPanel>
            </>
        )

    }

    // we are going to use withSelect in order for template to be available inside
    const ShowRoomPanelWithSelect = withSelect(select => {
        return {
            template: select('core/editor').getEditedPostAttribute('template')
        };
    })(ShowRoomPanel);

    registerPlugin('folio-showroom-panel', { render: ShowRoomPanelWithSelect });

})(window.wp);
