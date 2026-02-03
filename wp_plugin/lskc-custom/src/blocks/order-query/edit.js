import { InspectorControls, InnerBlocks } from '@wordpress/block-editor';
import { PanelBody, QueryControls } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
    const { query } = attributes;

    return (
        <>
            <InspectorControls>
                <PanelBody title="Order Query">
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
                allowedBlocks={['wc/order-template']}
                template={[['wc/order-template']]}
            />
        </>
    );
}
