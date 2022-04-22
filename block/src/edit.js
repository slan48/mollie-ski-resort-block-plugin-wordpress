/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Utility to make WordPress REST API requests. It’s a wrapper around window.fetch.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-api-fetch/
 */
import apiFetch from '@wordpress/api-fetch';

/**
 * ComboboxControl is an enhanced version of a SelectControl, with the addition of being able to search for options using a search input.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/components/combobox-control/
 */
import { ComboboxControl } from '@wordpress/components';

/**
 * Spinner is a component used to notify users that their action is being processed.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/components/spinner/
 */
import { Spinner } from '@wordpress/components';

/**
 * useState hook from React
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-element/#usestate
 */
import { useState } from '@wordpress/element';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * Mollie ski resort block, used to render the information of the fetched ski resort
 */
import MollieSkiResortBlock from "./components/MollieSkiResortBlock";

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
	const { searchValue, skiResort } = attributes;
	const blockProps = useBlockProps();
	const [suggestions, setSuggestions] = useState([]);

	const getSuggestions = async (search) => {
		const { body: { result } } = await apiFetch( { path: `/mollie-ski-resort/v1/suggest/autocomplete?search=${search}` } );
		if (result && result.length){
			return setSuggestions(result);
		}
		setSuggestions([]);
	}

	const getSkiResortDetails = async (search) => {
		setAttributes({
			searchValue: search,
			skiResort: {loading: true, info: {}}
		})

		const { body } = await apiFetch( { path: `/mollie-ski-resort/v1/search?search=${search}` } );
		if (body && body.name){
			return setAttributes({
				skiResort: {loading: false, info: { ...body }}
			});
		}

		setAttributes({
			skiResort: {loading: false, info: {}}
		});
	}

	return (
		<div {...useBlockProps}>
			<ComboboxControl
				label="Search Ski Resort"
				value={ searchValue }
				onChange={ ( inputValue ) => getSkiResortDetails(inputValue) }
				options={ suggestions.map(suggestion => ({
					value: suggestion.name,
					label: suggestion.name
				})) }
				onFilterValueChange={ ( inputValue ) => getSuggestions(inputValue) }
			/>
			{ skiResort.loading && <Spinner /> }
			{ !skiResort.loading && skiResort.info.name &&
				<MollieSkiResortBlock skiResortInfo={skiResort.info} />
			}
		</div>
	);
}
