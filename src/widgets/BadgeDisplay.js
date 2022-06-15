import store, { ACTIONS, get_settings } from './Store';
import { get_proofratings, copy_shortcode } from '../global';

import DisplaySquare from '../Display/Square';
import DisplayIcon from '../Display/Icon';
import DisplayBasic from '../Display/Basic';
import DisplayRectangle from '../Display/Rectangle';
import DisplayOverallRectangle from '../Display/OverallRectangle';
import DisplayOverallNarrow from '../Display/OverallNarrow';
import PreviewCTABanner from '../Display/CTABanner'

const { useState, useEffect } = React;

const BadgeDisplay = (props) => {

    const [settings, setSettings] = useState(get_settings())

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setSettings(get_settings()))
        return () => unsubscribe();
    }, [])

    const badge_display = Object.assign({
        widget_square: false,
        widget_basic: false,
        widget_icon: false,
        widget_rectangle: false,
        overall_cta_banner: false,
        overall_rectangle_embed: false,
        overall_rectangle_float: false,
        overall_narrow_embed: false,
        overall_narrow_float: false
    }, props?.badge_display);

    const update_single = (name) => {
        badge_display[name] = !badge_display[name];
        store.dispatch({ type: ACTIONS.BADGE_DISPLAY, payload: badge_display });

        props.save_now({ ...get_settings(), badge_display });
    }

    const handle_copy_shortcode = (attrs, event) => {
        attrs.id = props.id;
        copy_shortcode(attrs, event);
    }

    const handle_edit = (current_tab) => store.dispatch({ type: ACTIONS.UPDATE_SETTINGS, payload: { current_tab } })

    const on_summary_tab_click = (e, overview_summary_tab) => {
        if (e.target.open === false) {
            return;
        }

        store.dispatch({ type: ACTIONS.UPDATE_SETTINGS, payload: { overview_summary_tab } });
    }


    return (
        <React.Fragment>

            <div className="intro-text">
                <h3>Creating Rating Badges</h3>
                <p>This is your main hub for creating and customizing your rating trust badges. The three main categories of badges are below and you can activate as many badges as you want to add to your website. Once badges are on your website, tracking will automatically begin.</p>
                <p>To activate embeddable badges, toggle on 'Activate' and 'Edit' to customize the color and design of the rating badge. Each embeddable badge, has a shortcode associated with it. Once you are happy with the customization, copy and paste the shortcode to the pages you want to display the badges on.</p>
                <p>To activate floating badges, toggle on 'Activate' and 'Edit' to customize the design and layout. Floating badges will automatically appear on the pages you select under the 'Edit' section for each badge.</p>
                <p>To activate the call-to-action banner, toggle on 'Activate' and 'Edit' to customize the design and layout. The banner will automatically appear on the pages you select under the 'Edit' section for the banner. If you add buttons to the banner, use the webhook to track conversions.</p>
            </div>

            <div className="gap-30" />

            <details className="badge-overview-item" onToggle={(e) => on_summary_tab_click(e, 'embedded-badges')} open={settings?.overview_summary_tab == 'embedded-badges'}>
                <summary>
                    <h4>Embedded Badges</h4>
                    Add individual site ratings or your overall rating to any page.
                </summary>

                <div className="intro-text">
                    <p>The embed rating badges will display your ratings for each individual review site or you can also display your overall rating.</p>
                    <p>Activate the badge(s) you want to display, edit to customize, and copy paste the shortcode on the page(s) where you would like to dislay the badge.</p>
                    <p>Each badge when clicked will direct the visitor to the pertaining review site or the URL you provide.</p>
                </div>

                <div className="gap-20"></div>

                <ul className="badge-items-grid">
                    <li>
                        <div className="preview-widget">
                            <DisplaySquare {...get_settings()?.widget_square} />
                        </div>

                        <label className="label-switch-checkbox">
                            <input className="checkbox-switch" type="checkbox" onChange={() => update_single('widget_square')} checked={badge_display?.widget_square} />
                            <span>Deactivate</span>
                            <span>Activate</span>
                        </label>

                        {badge_display?.widget_square && <a className="btn-copy-shortcode" href="#" onClick={(event) => handle_copy_shortcode({ style: 'square' }, event)} >Copy Shortcode</a>}
                        {badge_display?.widget_square && <a className="button button-primary" onClick={() => handle_edit('widget_square')} >EDIT BADGE</a>}
                    </li>

                    <li>
                        <div className="preview-widget">
                            <DisplayIcon />
                        </div>
                        <label className="label-switch-checkbox">
                            <input className="checkbox-switch" type="checkbox" onChange={() => update_single('widget_icon')} checked={badge_display?.widget_icon} />
                            <span>Deactivate</span>
                            <span>Activate</span>
                        </label>

                        {badge_display?.widget_icon && <a className="btn-copy-shortcode" href="#" onClick={(event) => handle_copy_shortcode({ style: 'icon' }, event)} >Copy Shortcode</a>}
                        {badge_display?.widget_icon && <a className="button button-primary" onClick={() => handle_edit('widget_icon')} >EDIT BADGE</a>}
                    </li>

                    <li>
                        <div className="preview-widget">
                            <DisplayBasic />
                        </div>
                        <label className="label-switch-checkbox">
                            <input className="checkbox-switch" type="checkbox" onChange={() => update_single('widget_basic')} checked={badge_display?.widget_basic} />
                            <span>Deactivate</span>
                            <span>Activate</span>
                        </label>

                        {badge_display?.widget_basic && <a className="btn-copy-shortcode" href="#" onClick={(event) => handle_copy_shortcode({ style: 'basic' }, event)} >Copy Shortcode</a>}
                        {badge_display?.widget_basic && <a className="button button-primary" onClick={() => handle_edit('widget_basic')} >EDIT BADGE</a>}
                    </li>

                    <li>
                        <div className="preview-widget">
                            <DisplayRectangle />
                        </div>
                        <label className="label-switch-checkbox">
                            <input className="checkbox-switch" type="checkbox" onChange={() => update_single('widget_rectangle')} checked={badge_display?.widget_rectangle} />
                            <span>Deactivate</span>
                            <span>Activate</span>
                        </label>

                        {badge_display?.widget_rectangle && <a className="btn-copy-shortcode" href="#" onClick={(event) => handle_copy_shortcode({ style: 'rectangle' }, event)} >Copy Shortcode</a>}
                        {badge_display?.widget_rectangle && <a className="button button-primary" onClick={() => handle_edit('widget_rectangle')} >EDIT BADGE</a>}
                    </li>

                    <li>
                        <div className="preview-widget">
                            <DisplayOverallRectangle {...settings.overall_rectangle_embed} />
                        </div>

                        <label className="label-switch-checkbox">
                            <input className="checkbox-switch" type="checkbox" checked={badge_display?.overall_rectangle_embed} onChange={() => update_single('overall_rectangle_embed')} />
                            <span>Deactivate</span>
                            <span>Activate</span>
                        </label>

                        {badge_display?.overall_rectangle_embed && <a className="btn-copy-shortcode" href="#" onClick={(event) => handle_copy_shortcode({ slug: 'proofratings_overall_rectangle' }, event)} >Copy Shortcode</a>}
                        {badge_display?.overall_rectangle_embed && <a className="button button-primary" onClick={() => handle_edit('overall_rectangle_embed')} >EDIT BADGE</a>}
                    </li>

                    <li>
                        <div className="preview-widget">
                            <DisplayOverallNarrow {...settings.overall_narrow_embed} />
                        </div>
                        <label className="label-switch-checkbox">
                            <input className="checkbox-switch" type="checkbox" onChange={() => update_single('overall_narrow_embed')} checked={badge_display?.overall_narrow_embed} />
                            <span>Deactivate</span>
                            <span>Activate</span>
                        </label>

                        {badge_display?.overall_narrow_embed && <a className="btn-copy-shortcode" href="#" onClick={(event) => handle_copy_shortcode({ slug: 'proofratings_overall_narrow' }, event)} >Copy Shortcode</a>}
                        {badge_display?.overall_narrow_embed && <a className="button button-primary" onClick={() => handle_edit('overall_narrow_embed')} >EDIT BADGE</a>}
                    </li>
                </ul>


            </details>

            <details className="badge-overview-item" onToggle={(e) => on_summary_tab_click(e, 'floating-badges')} open={settings?.overview_summary_tab == 'floating-badges'}>
                <summary>
                    <h4>Floating Badges</h4>
                    Display your overall rating floating at the bottom of the screen
                </summary>

                <div className="proofratings-row">

                    <ul className="badge-items-grid">
                        <li>
                            <div className="preview-widget">
                                <DisplayOverallRectangle {...settings.overall_rectangle_float} className="preview-badge-float" />
                            </div>

                            <label className="label-switch-checkbox">
                                <input className="checkbox-switch" type="checkbox" checked={badge_display?.overall_rectangle_float} onChange={() => update_single('overall_rectangle_float')} />
                                <span>Deactivate</span>
                                <span>Activate</span>
                            </label>

                            {badge_display?.overall_rectangle_float && <a className="button button-primary" onClick={() => handle_edit('overall_rectangle_float')} >EDIT BADGE</a>}
                        </li>

                        <li>
                            <div className="preview-widget">
                                <DisplayOverallNarrow {...settings.overall_narrow_float} className="preview-badge-float" />
                            </div>
                            <label className="label-switch-checkbox">
                                <input className="checkbox-switch" type="checkbox" onChange={() => update_single('overall_narrow_float')} checked={badge_display?.overall_narrow_float} />
                                <span>Deactivate</span>
                                <span>Activate</span>
                            </label>

                            {badge_display?.overall_narrow_float && <a className="button button-primary" onClick={() => handle_edit('overall_narrow_float')} >EDIT BADGE</a>}
                        </li>
                    </ul>

                    <div className="intro-text" style={{ maxWidth: 390 }}>
                        <p>The floating overall rating badges will display your overall rating and float on the bottom of the page(s) you select.</p>
                        <p>The floating badges grab attention and are interactive. When clicked, the badge converts to individual review site breakdown. Each site can be clicked to take the visitor to the relevant review site.</p>
                        <p>When activated, schema helps boost your organic search ranking and show your star rating in search results for the page(s) it is displayed on.</p>
                    </div>
                </div>
            </details>

            <details className="badge-overview-item" onToggle={(e) => on_summary_tab_click(e, 'call-to-action-banner')} open={settings?.overview_summary_tab == 'call-to-action-banner'}>
                <summary>
                    <h4>Call-to-Action Banner</h4>
                    Track conversions with an overall rating displayed on a banner at the bottom of any page
                </summary>

                <div className="proofratings-row">
                    <ul className="badge-items-grid">
                        <li style={{ maxWidth: 'none' }}>
                            <div className="preview-widget" style={{minHeight: 0}}>
                                <PreviewCTABanner {...settings.overall_cta_banner} />
                            </div>

                            <label className="label-switch-checkbox">
                                <input className="checkbox-switch" type="checkbox" checked={badge_display?.overall_cta_banner} onChange={() => update_single('overall_cta_banner')} />
                                <span>Deactivate</span>
                                <span>Activate</span>
                            </label>

                            {badge_display?.overall_cta_banner && <a className="button button-primary" onClick={() => handle_edit('overall_cta_banner')} >EDIT BADGE</a>}
                        </li>
                    </ul>

                    <div className="intro-text" style={{ maxWidth: 390 }}>
                        <p>The overall rating banner will be displayed on any page(s) you add it to at the bottom of the screen and will only appear when the visitor scrolls downs.</p>
                        <p>The banner combines all lof your reviews together to show a total review count and rating. Schema is added to the banner to provide organic search advantages and show your overall star rating in search result.</p>
                        <p>You have the option to show the banner without buttons or you may add up to two call-to-action buttons. Using webhook, you can track conversions that come from your banner</p>
                    </div>
                </div>
            </details>
        </React.Fragment>
    );
};

export default BadgeDisplay;