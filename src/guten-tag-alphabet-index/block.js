/**
 * BLOCK: tag-groups-alphabetical-tag-index
 *
 *
 * @package     Tag Groups
 * @author      Christoph Amthor
 * @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
 * @license     GPL-3.0+
 * @since       0.38
 */

import '../editor.css';

import Select from '../components/tag-groups-select';
import HeadingToolbar from '../components/heading-toolbar';
import TagGroupsServerSideRender from '../components/tag-groups-render';

import TagGroupsHelp from '../components/tag-groups-help';

import {
  getIncludeOptions,
  getExcludeOptions,
  getTaxonomyOptions,
  optionsOrderby,
  optionsOrder,
  optionsTarget,
} from '../modules/functions';

import {
  handleChangeInclude,
  handleChangeExclude,
  handleChangeTaxonomy,
  toggleOptionShowTagCount,
  toggleOptionKeepTogether,
} from '../modules/handlers';

import {
  getGroupsFromApi,
  getTaxonomiesFromApi,
  getPostsFromApi,
} from '../modules/api';

const { __ } = wp.i18n;

const { createBlock, registerBlockType } = wp.blocks;

const { InspectorControls, PlainText } = wp.editor;

const { PanelBody, ToggleControl, RangeControl } = wp.components;

const { Component, Fragment } = wp.element;

const {
  siteUrl,
  siteLang,
  pluginUrl,
  hasPremium,
  serverSideRender,
  gutenbergSettings,
} = ChattyMangoTagGroupsGlobal;

const helpUrl = '';

class editFunction extends Component {
  // Method for setting the initial state.
  static getInitialState(attributes) {
    let selectedInclude = []; // empty means all
    let selectedExclude = []; // empty means none
    let selectedTaxonomies = ['post_tag'];
    let uniqueId =
      'tag_groups_render_' + Math.random().toString(36).substring(7);

    // We need arrays for the select elements.
    if (attributes.include) {
      selectedInclude = attributes.include.split(',').map((x) => {
        return parseInt(x, 10);
      });
    }
    if (attributes.exclude) {
      selectedExclude = attributes.exclude.split(',').map((x) => {
        return parseInt(x, 10);
      });
    }

    if (attributes.taxonomy) {
      selectedTaxonomies = attributes.taxonomy.split(',');
    }

    return {
      groups: [],
      taxonomies: [],
      posts: [],
      selectedTaxonomies, // array representation
      selectedInclude, // array representation
      selectedExclude,
      uniqueId,
    };
  }

  // Constructing our component. With super() we are setting everything to 'this'.
  // Now we can access the attributes with this.props.attributes
  constructor() {
    super(...arguments);

    const { attributes, setAttributes } = this.props;

    this.groupsEndPoint = '/tag-groups/v1/groups';
    this.termsEndPoint = '/tag-groups/v1/terms';
    this.taxonomiesEndPoint = '/tag-groups/v1/taxonomies';

    this.state = this.constructor.getInitialState(attributes);

    if (!attributes.hide_empty) {
      setAttributes({ threshold: 0 });
    }

    if (attributes.threshold) {
      setAttributes({ hide_empty: 1 });
    } else {
      setAttributes({ hide_empty: 0 });
    }

    if (hasPremium) {
      this.helpProduct = 'tag-groups-premium';
      this.helpFeature =
        'alphabetical-tag-index/alphabetical-tag-index-gutenberg-block/';
    } else {
      this.helpProduct = 'tag-groups';
      this.helpFeature =
        'alphabetical-tag-index-tag-clouds-and-groups-info/alphabetical-tag-index-gutenberg-block-2/';
    }

    // Load data from REST API.
    getGroupsFromApi(this);
    getTaxonomiesFromApi(this);
    getPostsFromApi(this);
  }

  render() {
    const { attributes, setAttributes } = this.props;

    const {
      amount,
      append,
      assigned_class,
      column_count,
      column_gap,
      cover,
      custom_title,
      custom_title_zero,
      custom_title_plural,
      div_class,
      div_id,
      exclude_letters,
      h_level,
      header_class,
      include_letters,
      keep_together,
      largest,
      link_append,
      link_target,
      order,
      orderby,
      prepend,
      show_tag_count,
      smallest,
      tags_div_class,
      tags_post_id,
      threshold,
    } = attributes;

    if (attributes.source !== 'gutenberg') {
      setAttributes({ source: 'gutenberg' });
    }

    let renderAttributes = { ...attributes };
    renderAttributes.div_id = this.state.uniqueId;
    delete renderAttributes.cover;
    delete renderAttributes.link_append;
    delete renderAttributes.link_target;
    delete renderAttributes.custom_title;
    delete renderAttributes.custom_title_zero;
    delete renderAttributes.custom_title_plural;
    delete renderAttributes.assigned_class;

    if (0 === tags_post_id) {
      renderAttributes.tags_post_id = wp.data
        .select('core/editor')
        .getCurrentPostId();
    }

    return [
      <InspectorControls key='inspector'>
        <div className='chatty-mango-inspector-control'>
          <PanelBody title={__('Tags and Taxonomies')} initialOpen={false}>
            <TagGroupsHelp
              url={helpUrl}
              product={this.helpProduct}
              feature={this.helpFeature}
              siteLang={siteLang}
              topic='taxonomy'
            />
            <label htmlFor='tg_input_taxonomy'>
              {__('Include taxonomies')}
            </label>
            <Select
              id='tg_input_taxonomy'
              onChange={(options) => handleChangeTaxonomy(this, options)}
              value={this.state.selectedTaxonomies}
              options={getTaxonomyOptions(this)}
              multi={true}
              closeOnSelect={false}
              removeSelected={true}
            />
            <TagGroupsHelp
              url={helpUrl}
              product={this.helpProduct}
              feature={this.helpFeature}
              siteLang={siteLang}
              topic='smallest'
            />
            <RangeControl
              label={__('Smallest font size')}
              value={smallest !== undefined ? Number(smallest) : 12}
              onChange={(smallest) => {
                if (smallest <= largest && smallest < 73)
                  setAttributes({ smallest });
              }}
              min={6}
              max={72}
            />
            <TagGroupsHelp
              url={helpUrl}
              product={this.helpProduct}
              feature={this.helpFeature}
              siteLang={siteLang}
              topic='largest'
            />
            <RangeControl
              label={__('Largest font size')}
              value={largest !== undefined ? Number(largest) : 22}
              onChange={(largest) => {
                if (smallest <= largest && largest > 5)
                  setAttributes({ largest });
              }}
              min={6}
              max={72}
            />
            <TagGroupsHelp
              url={helpUrl}
              product={this.helpProduct}
              feature={this.helpFeature}
              siteLang={siteLang}
              topic='amount'
            />
            <RangeControl
              label={
                __('Tags per section') +
                (amount == 0 ? ': ' + __('unlimited') : '')
              }
              value={amount !== undefined ? Number(amount) : 0}
              onChange={(amount) => setAttributes({ amount })}
              min={0}
              max={200}
            />
            <TagGroupsHelp
              url={helpUrl}
              product={this.helpProduct}
              feature={this.helpFeature}
              siteLang={siteLang}
              topic='orderby'
            />
            <label htmlFor='tg_input_orderby'>{__('Order tags by')}</label>
            <Select
              id='tg_input_orderby'
              onChange={(option) => {
                if (option) setAttributes({ orderby: option.value });
              }}
              value={typeof orderby === 'string' ? orderby : ''}
              options={optionsOrderby}
            />
            {orderby !== 'random' && (
              <div>
                <TagGroupsHelp
                  url={helpUrl}
                  product={this.helpProduct}
                  feature={this.helpFeature}
                  siteLang={siteLang}
                  topic='order'
                />
                <label htmlFor='tg_input_order'>{__('Sort order')}</label>
                <Select
                  id='tg_input_order'
                  onChange={(option) => {
                    if (option) setAttributes({ order: option.value });
                  }}
                  value={
                    typeof order === 'string'
                      ? order.toUpperCase()
                      : ''
                  }
                  options={optionsOrder}
                />
              </div>
            )}
            <TagGroupsHelp
              url={helpUrl}
              product={this.helpProduct}
              feature={this.helpFeature}
              siteLang={siteLang}
              topic='threshold'
            />
            <RangeControl
              label={__('Minimum post count for tags to appear')}
              value={threshold !== undefined ? Number(threshold) : 0}
              onChange={(threshold) => {
                setAttributes({ threshold });
                if (0 === threshold) {
                  setAttributes({ hide_empty: 0 });
                } else {
                  setAttributes({ hide_empty: 1 });
                }
              }}
              min={0}
              max={50}
            />
            <TagGroupsHelp
              url={helpUrl}
              product={this.helpProduct}
              feature={this.helpFeature}
              siteLang={siteLang}
              topic='prepend'
            />
            <div>
              <label htmlFor='tg_input_prepend'>{__('Prepend')}</label>
            </div>
            <PlainText
              id='tg_input_prepend'
              className='input-control'
              value={prepend ? prepend : ''}
              placeholder={__('Write here or leave empty.')}
              onChange={(prepend) => setAttributes({ prepend })}
            />
            <TagGroupsHelp
              url={helpUrl}
              product={this.helpProduct}
              feature={this.helpFeature}
              siteLang={siteLang}
              topic='append'
            />
            <div>
              <label htmlFor='tg_input_append'>{__('Append')}</label>
            </div>
            <PlainText
              id='tg_input_append'
              className='input-control'
              value={append ? append : ''}
              placeholder={__('Write here or leave empty.')}
              onChange={(append) => setAttributes({ append })}
            />
            {!custom_title && (
              <div>
                <TagGroupsHelp
                  url={helpUrl}
                  product={this.helpProduct}
                  feature={this.helpFeature}
                  siteLang={siteLang}
                  topic='show_tag_count'
                />
                <ToggleControl
                  label={__('Show post count in the tooltip')}
                  checked={show_tag_count}
                  onChange={() => toggleOptionShowTagCount(this)}
                />
              </div>
            )}
            {attributes.hide_empty === 0 && threshold < 1 ? (
              <div>
                <div>
                  <TagGroupsHelp
                    url={helpUrl}
                    product={this.helpProduct}
                    feature={this.helpFeature}
                    siteLang={siteLang}
                    topic='custom_title_zero'
                  />
                  <label htmlFor='tg_input_custom_title_zero'>
                    {__('Custom title for post count 0')}
                  </label>
                </div>
                <PlainText
                  id='tg_input_custom_title_zero'
                  className='input-control'
                  value={custom_title_zero ? custom_title_zero : ''}
                  placeholder={__('Write here or leave empty.')}
                  onChange={(custom_title_zero) =>
                    setAttributes({ custom_title_zero })
                  }
                />
              </div>
            ) : (
              ''
            )}
            <div>
              <TagGroupsHelp
                url={helpUrl}
                product={this.helpProduct}
                feature={this.helpFeature}
                siteLang={siteLang}
                topic='custom_title'
              />
              <label htmlFor='tg_input_custom_title'>
                {__('Custom title for post count 1')}
              </label>
            </div>
            <PlainText
              id='tg_input_custom_title'
              className='input-control'
              value={custom_title ? custom_title : ''}
              placeholder={__('Write here or leave empty.')}
              onChange={(custom_title) => setAttributes({ custom_title })}
            />
            <div>
              <TagGroupsHelp
                url={helpUrl}
                product={this.helpProduct}
                feature={this.helpFeature}
                siteLang={siteLang}
                topic='custom_title_plural'
              />
              <label htmlFor='tg_input_custom_title_plural'>
                {__('Custom title for post count > 1')}
              </label>
            </div>
            <PlainText
              id='tg_input_custom_title_plural'
              className='input-control'
              value={custom_title_plural ? custom_title_plural : ''}
              placeholder={__('Write here or leave empty.')}
              onChange={(custom_title_plural) => setAttributes({ custom_title_plural })}
            />
            <TagGroupsHelp
              url={helpUrl}
              product={this.helpProduct}
              feature={this.helpFeature}
              siteLang={siteLang}
              topic='link_target'
            />
            <label htmlFor='tg_input_link_target'>{__('Link target')}</label>
            <Select
              id='tg_input_link_target'
              onChange={(option) => {
                if (option) setAttributes({ link_target: option.value });
              }}
              value={
                typeof link_target === 'string'
                  ? link_target
                  : ''
              }
              options={optionsTarget}
            />
            <div>
              <label htmlFor='tg_input_link_append'>
                {__('Append to link')}
              </label>
            </div>
            <PlainText
              id='tg_input_link_append'
              className='input-control'
              value={link_append ? link_append : ''}
              placeholder={__('Write here or leave empty.')}
              onChange={(link_append) => setAttributes({ link_append })}
            />
            <TagGroupsHelp
              url={helpUrl}
              product={this.helpProduct}
              feature={this.helpFeature}
              siteLang={siteLang}
              topic='tags_post_id'
            />
            <label htmlFor='tg_input_tags_post_id'>
              {__('Use tags of the following post:')}
            </label>
            <Select
              id='tg_input_tags_post_id'
              onChange={(option) => {
                if (option && option.value > -2)
                  setAttributes({ tags_post_id: option.value });
              }}
              value={tags_post_id}
              options={this.state.posts}
            />
          </PanelBody>

          <PanelBody title={__('Alphabet')} initialOpen={false}>
            <div>
              <TagGroupsHelp
                url={helpUrl}
                product={this.helpProduct}
                feature={this.helpFeature}
                siteLang={siteLang}
                topic='include_letters'
              />
              <label htmlFor='tg_input_include_letters'>
                {'Include letters'}
              </label>
            </div>
            <PlainText
              id='tg_input_include_letters'
              className='input-control'
              value={include_letters ? include_letters : ''}
              placeholder={__('Write here or leave empty.')}
              onChange={(include_letters) => setAttributes({ include_letters })}
            />
            <div>
              <TagGroupsHelp
                url={helpUrl}
                product={this.helpProduct}
                feature={this.helpFeature}
                siteLang={siteLang}
                topic='exclude_letters'
              />
              <label htmlFor='tg_input_exclude_letters'>
                {'Exclude letters'}
              </label>
            </div>
            <PlainText
              id='tg_input_exclude_letters'
              className='input-control'
              value={exclude_letters ? exclude_letters : ''}
              placeholder={__('Write here or leave empty.')}
              onChange={(exclude_letters) => setAttributes({ exclude_letters })}
            />
          </PanelBody>

          <PanelBody title={__('Groups')} initialOpen={false}>
            <TagGroupsHelp
              url={helpUrl}
              product={this.helpProduct}
              feature={this.helpFeature}
              siteLang={siteLang}
              topic='include'
            />
            <label htmlFor='tg_input_include'>{__('Include groups')}</label>
            <Select
              id='tg_input_include'
              onChange={(options) => handleChangeInclude(this, options)}
              value={this.state.selectedInclude}
              options={getIncludeOptions(this)}
              multi={true}
              closeOnSelect={false}
              removeSelected={true}
            />
            <TagGroupsHelp
              url={helpUrl}
              product={this.helpProduct}
              feature={this.helpFeature}
              siteLang={siteLang}
              topic='exclude'
            />
            <label htmlFor='tg_input_exclude'>{__('Exclude groups')}</label>
            <Select
              id='tg_input_exclude'
              onChange={(options) => handleChangeExclude(this, options)}
              value={this.state.selectedExclude}
              options={getExcludeOptions(this)}
              multi={true}
              closeOnSelect={false}
              removeSelected={true}
            />
          </PanelBody>

          <PanelBody title={__('Columns')} initialOpen={false}>
            <TagGroupsHelp
              url={helpUrl}
              product={this.helpProduct}
              feature={this.helpFeature}
              siteLang={siteLang}
              topic='column_count'
            />
            <RangeControl
              label={__('Column count')}
              value={column_count >= 0 ? Number(column_count) : 2}
              onChange={(value) => setAttributes({ column_count: value })}
              min={0}
              max={10}
            />
            {column_count > 1 && (
              <div>
                <div>
                  <TagGroupsHelp
                    url={helpUrl}
                    product={this.helpProduct}
                    feature={this.helpFeature}
                    siteLang={siteLang}
                    topic='column_gap'
                  />
                  <label htmlFor='tg_input_column_gap'>
                    {'Gap between columns'}
                  </label>
                </div>
                <PlainText
                  id='tg_input_column_gap'
                  className='input-control'
                  value={column_gap ? column_gap : '10px'}
                  placeholder={__('Write here or leave empty.')}
                  onChange={(column_gap) => setAttributes({ column_gap })}
                />
                <TagGroupsHelp
                  url={helpUrl}
                  product={this.helpProduct}
                  feature={this.helpFeature}
                  siteLang={siteLang}
                  topic='keep_together'
                />
                <ToggleControl
                  label={__('Avoid breaking groups across columns')}
                  checked={keep_together}
                  onChange={() => toggleOptionKeepTogether(this)}
                />
              </div>
            )}
          </PanelBody>

          <PanelBody title={__('Advanced Styling')} initialOpen={false}>
            <div>
              <TagGroupsHelp
                url={helpUrl}
                product={this.helpProduct}
                feature={this.helpFeature}
                siteLang={siteLang}
                topic='h_level'
              />
              <label htmlFor='tg_input_h_level'>{'Title element'}</label>
            </div>
            <HeadingToolbar
              id='tg_input_h_level'
              isCollapsed={false}
              minLevel={1}
              maxLevel={7}
              selectedLevel={h_level}
              onChange={(newLevel) => setAttributes({ h_level: newLevel })}
            />
            <div>
              <TagGroupsHelp
                url={helpUrl}
                product={this.helpProduct}
                feature={this.helpFeature}
                siteLang={siteLang}
                topic='div_id'
              />
              <label htmlFor='tg_input_div_id'>{'div id'}</label>
            </div>
            <PlainText
              id='tg_input_div_id'
              className='input-control'
              value={div_id ? div_id : ''}
              placeholder={__('Write here or leave empty.')}
              onChange={(div_id) => setAttributes({ div_id })}
            />
            <div>
              <TagGroupsHelp
                url={helpUrl}
                product={this.helpProduct}
                feature={this.helpFeature}
                siteLang={siteLang}
                topic='div_class'
              />
              <label htmlFor='tg_input_div_class'>{'div class'}</label>
            </div>
            <PlainText
              id='tg_input_div_class'
              className='input-control'
              value={div_class ? div_class : ''}
              placeholder={__('Write here or leave empty.')}
              onChange={(div_class) => setAttributes({ div_class })}
            />
            <div>
              <TagGroupsHelp
                url={helpUrl}
                product={this.helpProduct}
                feature={this.helpFeature}
                siteLang={siteLang}
                topic='tags_div_class'
              />
              <label htmlFor='tg_input_tags_div_class'>
                {'tags div class'}
              </label>
            </div>
            <PlainText
              id='tg_input_tags_div_class'
              className='input-control'
              value={tags_div_class ? tags_div_class : ''}
              placeholder={__('Write here or leave empty.')}
              onChange={(tags_div_class) => setAttributes({ tags_div_class })}
            />
            <div>
              <TagGroupsHelp
                url={helpUrl}
                product={this.helpProduct}
                feature={this.helpFeature}
                siteLang={siteLang}
                topic='header_class'
              />
              <label htmlFor='tg_input_header_class'>{'header class'}</label>
            </div>
            <PlainText
              id='tg_input_header_class'
              className='input-control'
              value={header_class ? header_class : ''}
              placeholder={__('Write here or leave empty.')}
              onChange={(header_class) => setAttributes({ header_class })}
            />
            {tags_post_id !== -1 && (
              <div>
                <div>
                  <TagGroupsHelp
                    url={helpUrl}
                    product={this.helpProduct}
                    feature={this.helpFeature}
                    siteLang={siteLang}
                    topic='assigned_class'
                  />
                  <label htmlFor='tg_input_assigned_class'>
                    {'<a class="..._0"> or <a class="..._1">'}
                  </label>
                </div>
                <PlainText
                  id='tg_input_assigned_class'
                  className='input-control'
                  value={assigned_class ? assigned_class : ''}
                  placeholder={__('Write here or leave empty.')}
                  onChange={(assigned_class) =>
                    setAttributes({ assigned_class })
                  }
                />
              </div>
            )}
          </PanelBody>
        </div>
      </InspectorControls>,
      <div>
        {!!cover && (
          <Fragment>
            <img src={pluginUrl + '/assets/images/features/' + cover} />
          </Fragment>
        )}
        {!cover && serverSideRender && (
          <TagGroupsServerSideRender
            block='chatty-mango/tag-groups-alphabetical-tag-index'
            className='chatty-mango-not-active'
            attributes={renderAttributes}
          />
        )}
        {!cover && !serverSideRender && (
          <div className='chatty-mango-editor'>
            <div className='chatty-mango-editor-block'>
            </div>
            <div className='chatty-mango-editor-block'>
              <h3 className='chatty-mango-editor-title'>
                {__('Alphabetical Tag Index')}
              </h3>
              <div className='cm-gutenberg dashicons-before dashicons-admin-generic'>
                {__(
                  'Select this block and customize the tag cloud in the Inspector.'
                )}
              </div>
            </div>
          </div>
        )}
      </div>,
    ];
  }
}

/**
 * Register: a Gutenberg Block.
 *
 * @param  {string}	  name	   Block name.
 * @param  {Object}	  settings Block settings.
 * @return {?WPBlock}		   The block, if it has been successfully
 *							   registered; otherwise `undefined`.
 */
var cmTagGroupsAlphabetBlock = registerBlockType(
  'chatty-mango/tag-groups-alphabetical-tag-index',
  {
    title: __('Alphabetical Tag Index'),
    icon: 'tag', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
    category: 'chatty-mango',
    description: __('Show your tags in a list sorted by first letters.'),
    keywords: [__('alphabet'), __('tag cloud'), 'PublishPress'],
    html: false,
    transforms: {
      to: [
        {
          type: 'block',
          blocks: ['core/shortcode'],
          transform: function (attributes) {
            let parameters = [];
            for (var attribute in attributes) {
              if (attributes.hasOwnProperty(attribute)) {
                if (
                  null !== attributes[attribute] &&
                  '' !== attributes[attribute] &&
                  'source' !== attribute &&
                  cmTagGroupsAlphabetBlock.attributes[attribute] &&
                  attributes[attribute] !==
                    cmTagGroupsAlphabetBlock.attributes[attribute].default
                ) {
                  if (typeof attributes[attribute] === 'number') {
                    parameters.push(attribute + '=' + attributes[attribute]);
                  } else {
                    if (attributes[attribute].indexOf('"') === -1) {
                      parameters.push(
                        attribute + '="' + attributes[attribute] + '"'
                      );
                    } else {
                      parameters.push(
                        attribute + "='" + attributes[attribute] + "'"
                      );
                    }
                  }
                }
              }
            }

            let text =
              '[tag_groups_alphabetical_index ' + parameters.join(' ') + ']';
            return createBlock('core/shortcode', {
              text,
            });
          },
        },
      ],
    },
    supports: {
      html: false,
      customClassName: false,
    },
    example: {
      attributes: {
        cover: 'alphabetical-tag-index.png',
      },
    },

    /**
     * Attributes are the same as shortcode parameters
     **/
    attributes: {
      cover: {
        type: 'string',
        default: '',
      },
      source: {
        // internal indicator to identify Gutenberg blocks
        type: 'string',
        default: '',
      },
      amount: {
        // configurable in block
        type: 'integer',
        default: 0,
      },
      append: {
        // configurable in block
        type: 'string',
        default: '',
      },
      assigned_class: {
        // configurable in block
        type: 'string',
        default: '',
      },
      column_count: {
        type: 'integer',
        default: 2,
      },
      column_gap: {
        type: 'string',
        default: '10px',
      },
      custom_title: {
        // configurable in block
        type: 'string',
        default: '{description} ({count})',
      },
      custom_title_zero: {
        // configurable in block
        type: 'string',
        default: '{description} ({count})',
      },
      custom_title_plural: {
        // configurable in block
        type: 'string',
        default: '{description} ({count})',
      },
      div_class: {
        // configurable in block
        type: 'string',
        default: 'tag-groups-alphabetical-index',
      },
      div_id: {
        // configurable in block
        type: 'string',
        default: '',
      },
      exclude_letters: {
        // only in shortcode
        type: 'string',
        default: '',
      },
      exclude_terms: {
        // only in shortcode
        type: 'string',
        default: '',
      },
      h_level: {
        // configurable in block
        type: 'integer',
        default: 3,
      },
      header_class: {
        type: 'string',
        default: '',
      },
      hide_empty: {
        // configurable in block
        type: 'integer',
        default: 1,
      },
      include: {
        // configurable in block
        type: 'string',
        default: '',
      },
      include_letters: {
        // configurable in block
        type: 'string',
        default: '',
      },
      include_terms: {
        // only in shortcode
        type: 'string',
        default: '',
      },
      keep_together: {
        // configurable in block
        type: 'integer',
        default: 1,
      },
      largest: {
        // configurable in block
        type: 'integer',
        default: 12,
      },
      link_append: {
        // configurable in block
        type: 'string',
        default: '',
      },
      link_target: {
        // configurable in block
        type: 'string',
        default: '_self',
      },
      order: {
        // configurable in block
        type: 'string',
        default: 'ASC',
      },
      orderby: {
        // configurable in block
        type: 'string',
        default: 'name',
      },
      prepend: {
        // configurable in block
        type: 'string',
        default: '',
      },
      show_tag_count: {
        // configurable in block
        type: 'integer',
        default: 1,
      },
      smallest: {
        // configurable in block
        type: 'integer',
        default: 12,
      },
      tags_div_class: {
        // configurable in block
        type: 'string',
        default: 'tag-groups-alphabetical-index-tags',
      },
      tags_post_id: {
        // configurable in block
        type: 'integer',
        default: -1,
      },
      taxonomy: {
        // configurable in block
        type: 'string',
        default: '',
      },
      threshold: {
        // configurable in block
        type: 'integer',
        default: 0,
      },
    },

    /**
     * Composing and rendering the editor content and control elements
     */
    edit: editFunction,

    /**
     * We don't render any HTML when saving
     */
    save: (props) => null,
  }
);
