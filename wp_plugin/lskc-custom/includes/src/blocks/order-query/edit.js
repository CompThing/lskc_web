import { InspectorControls, InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, QueryControls } from '@wordpress/components';
import './editor.scss';

const MY_TEMPLATE = [
	[ 'core/image', {} ],
	[ 'core/heading', { placeholder: 'Order Heading' } ],
	[ 'core/paragraph', { placeholder: 'Order Summary' } ],
];

export default function Edit({ attributes, setAttributes }) {
    const { query } = attributes;
    const blockProps = useBlockProps();
    return (
        <>
            <InspectorControls>
                <PanelBody title="LSKC Order Query">
                    <QueryControls
                        numberOfItems={query.perPage}
                        order={query.order}
                        orderBy={query.orderBy}
                        onNumberOfItemsChange={(perPage) =>
                            setAttributes({ query: { ...query, perPage } })
                        }
                        onOrderChange={(order) =>
                            setAttributes({ query: { ...query, order } })
                        }
                        onOrderByChange={(orderBy) =>
                            setAttributes({ query: { ...query, orderBy } })
                        }
                    />
                </PanelBody>
            </InspectorControls>

            <InnerBlocks
                allowedBlocks={['lskc-custom/order-template']}
                template={[['lskc-custom/order-template']]}
            />
        </>
    );
}
