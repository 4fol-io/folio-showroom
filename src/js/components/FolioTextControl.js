/**
 * Folio Text Control React Component
 *
 * Handles Text control for post/page meta
 *
 * @package Folio
 */

const { useSelect, useDispatch } = wp.data
const { TextControl } = wp.components

const FolioTextControl = (props) => {

    const { metaValue } = useSelect((select) => {
        return {
            metaValue: select('core/editor').getEditedPostAttribute('meta')[props.metaKey],
        }
    }, [props.metaKey]);

    const { editPost } = useDispatch('core/editor');

    return (
        <TextControl
            type="text"
            label={props.label}
            help={props.help}
            value={metaValue}
            className={'folio-control folio-text-control'}
            onChange={(value) => editPost({ meta: { [props.metaKey]: value } })}
        />
    )

}

export default FolioTextControl;