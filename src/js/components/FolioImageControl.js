/**
 * Folio Image Control React Component
 *
 * Handles Image Media Upload Control to post/page meta
 *
 * @package Folio
 */

const { Button, Spinner, BaseControl } = window.wp.components
const { MediaUpload, MediaUploadCheck } = window.wp.blockEditor
const { useSelect, useDispatch } = window.wp.data
const { __ } = wp.i18n;

const FolioImageControl = (props) => {

	const { imageId, image } = useSelect(select => {

		const id = select('core/editor').getEditedPostAttribute('meta')[props.metaKey];

		return {
			imageId: id,
			image: select('core').getMedia(id),
		}
	})

	const { editPost } = useDispatch('core/editor', [imageId])

	const addLabel = props.addLabel || 'Upload image';
	const removeLabel = props.removeLabel || 'Remove image';

	return (
		<BaseControl id={props.metaKey} label={props.label} className={`folio-control folio-image-control ${props.className}`}>
			<MediaUploadCheck>
				<MediaUpload
					onSelect={(media) => editPost({ meta: { [props.metaKey]: media.id } })}
					allowedTypes={['image']}
					value={imageId}
					render={({ open }) => (
						<div>
							{!imageId && <Button variant="secondary" onClick={open}>{addLabel}</Button>}
							{!!imageId && !image &&
								<Spinner />
							}
							{!!image && image &&
								<Button variant="link" onClick={open}>
									<img src={image.source_url} />
								</Button>
							}
						</div>
					)}
				/>
			</MediaUploadCheck>
			{!!imageId &&
				<Button onClick={() => editPost({ meta: { [props.metaKey]: null } })} isLink isDestructive>
					{removeLabel}
				</Button>
			}
		</BaseControl>
	)
}

export default FolioImageControl