/**
 * Folio Select Control React Component
 *
 * Handles Toggle Control for post/page meta
 *
 * @package Folio
 */

const { useSelect, useDispatch } = wp.data
const { ToggleControl } = wp.components

const FolioToggleControl = (props) => {

    const { metaValue } = useSelect((select) => {
        return {
            metaValue: select('core/editor').getEditedPostAttribute('meta')[props.metaKey],
        }
    }, [props.metaKey]);

    const { bodyClassOn, label, help } = props;

    const { editPost } = useDispatch('core/editor');

    if (bodyClassOn && metaValue) {
        document.body.classList.add(bodyClassOn);
    }

    const toggle = (value) => {

        // Toggle body class if passed
        if (bodyClassOn) {
            if (value) {
                document.body.classList.add(bodyClassOn)
            } else {
                document.body.classList.remove(bodyClassOn)
            }
        }

        // Dispatch update post meta
        editPost({ meta: { [props.metaKey]: value } });
    }

    return (
        <ToggleControl
            label={label}
            help={help}
            checked={metaValue}
            className={'folio-control folio-toggle-control'}
            onChange={(value) => toggle(value)}
        />
    )

}

export default FolioToggleControl;