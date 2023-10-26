/**
 * Folio Select Control React Component
 *
 * Handles Select Control for post/page meta
 *
 * @package Folio
 */

const { useSelect, useDispatch } = wp.data
const { SelectControl } = wp.components

const FolioSelectControl = (props) => {

    const { metaValue } = useSelect((select) => {
        return {
            metaValue: select('core/editor').getEditedPostAttribute('meta')[props.metaKey],
        }
    }, [props.metaKey]);

    const { editPost } = useDispatch('core/editor');

    return (
        <SelectControl
            label={props.label}
            help={props.help}
            value={metaValue}
            className={'folio-control folio-select-control'}
            options={props.options}
            onChange={(value) => editPost({ meta: { [props.metaKey]: value } })}
        />
    )

}

export default FolioSelectControl;



