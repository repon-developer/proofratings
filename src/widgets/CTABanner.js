import store, { ACTIONS, get_settings } from "../widgets/Store";
import { get_proofrating } from "../global";
import ColorPicker from "./../Component/ColorPicker";

import Button from "../Component/Button";
import Pages from "../Component/Pages";

const { useState, useEffect } = React;

const CTABanner = (props) => {
    const [state, setState] = useState(get_settings().overall_cta_banner);

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setState(get_settings().overall_cta_banner));
        return () => unsubscribe();
    }, [])

    const handle_field = (data) => store.dispatch({ type: ACTIONS.OVERALL_CTA_BANNER, payload: data });

    const handle_button = (name, value) => {
        let button1 = typeof state.button1 === 'object' ? state.button1 : {};
        button1[name] = value;
        handle_field({ button1 })
    }

    const handle_button2 = (name, value) => {
        let button2 = typeof state.button2 === 'object' ? state.button2 : {};
        button2[name] = value;
        handle_field({ button2 })
    }

    const get_styles = () => {
        const styles = []
        if (state?.star_color) {
            styles.push('--star_color:' + state.star_color);
        }

        if (state?.background_color) {
            styles.push('--backgroundColor:' + state.background_color);
        }

        if (state?.rating_text_color) {
            styles.push('--rating_text_color:' + state.rating_text_color);
        }

        if (state?.review_rating_background_color) {
            styles.push('--review_rating_background_color:' + state.review_rating_background_color);
        }

        if (state?.number_review_text_color) {
            styles.push('--reviewCountTextcolor:' + state.number_review_text_color);
        }

        return styles;
    }

    const css_style = `.proofratings-banner-badge {${get_styles().join(';')}}`;

    const cta_button_container = () => {
        const button1 = Object.assign({ show: true, text: 'Sign Up' }, state.button1);
        const button2 = Object.assign({ show: true, text: 'Sign Up' }, state.button2);

        return (
            <div className="button-container">
                {(button1.show === true && button1.text.length) && <div className="proofratings-button button1 has-border">{button1.text}</div>}
                {/* {(button2.show && button2.text.length) && <div className="proofratings-button button1 has-border">{button2.text}</div>} */}
            </div>
        )
    }

    return (
        <React.Fragment>
            <style>{css_style}</style>

            <div className="proofratings-copyarea">
                <h3>Webhook URL</h3>
                <code className="shortocde-area">{get_proofrating().api}/webhooks?id={props?.id}&amp;site_url={get_proofrating().site_url}</code>
                <p className="description">
                    Use this URL to track conversions. And the URL to any software you use for <br /> your Call-to-action button(s). <br />
                    Note: Set the webhook as a POST.
                </p>
            </div>

            <div className="gap-40" />

            <div className="wrapper-page-selection">
                <div className="settings-column">
                    <h2 className="section-title-large" style={{ marginTop: 0 }}>Device Visibility</h2>
                    <table className="form-table">
                        <tbody>
                            <tr>
                                <th scope="row">Tablet Visibility</th>
                                <td>
                                    <label className="label-switch-checkbox">
                                        <input className="checkbox-switch" type="checkbox" checked={state?.tablet} onChange={() => handle_field({ tablet: !state?.tablet })} />
                                        <span>Hide on Tablet</span>
                                        <span>Show on Tablet</span>
                                    </label>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">Mobile Visibility</th>
                                <td>
                                    <label className="label-switch-checkbox">
                                        <input className="checkbox-switch" type="checkbox" checked={state?.mobile} onChange={() => handle_field({ mobile: !state?.mobile })} />
                                        <span>Hide on Mobile</span>
                                        <span>Show on Mobile</span>
                                    </label>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">Close option</th>
                                <td>
                                    <label className="label-switch-checkbox">
                                        <input className="checkbox-switch" type="checkbox" onChange={() => handle_field({ close_button: !state?.close_button })} checked={state?.close_button} />
                                        <span>Don't allow user to close/hide</span>
                                        <span>Allow close for mobile if click</span>
                                    </label>

                                    <div className="gap-5" />

                                    <p>Add close option when hovered on desktop. No close option available on mobile.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <h2 className="section-title-large" style={{ marginBottom: 0 }}>Color Selection</h2>
                    <table className="form-table">
                        <tbody>

                            <tr>
                                <td colSpan={2} style={{ paddingLeft: 0 }}>
                                    <div className={`proofratings-banner-badge badge-hidden-mobile ${state?.shadow ? 'has-shadow' : ''}`}>
                                        <div className="proofratings-logos">
                                            <img src={`${get_proofrating().assets_url}/images/icon-google.png`} alt="google" />
                                            <img src={`${get_proofrating().assets_url}/images/icon-wordpress.jpg`} alt="wordpress" />
                                        </div>
                                        <div className="rating-box">
                                            <span className="proofratings-stars medium">
                                                <i style={{ width: "100%" }} />
                                            </span>
                                            <span className="rating">5.0 / 5</span>
                                        </div>
                                        <div className="proofratings-review-count"># customer reviews</div>
                                        {cta_button_container()}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Star Color</th>
                                <td><ColorPicker color={state?.star_color} onUpdate={(star_color) => handle_field({ star_color })} /></td>
                            </tr>

                            <tr>
                                <th scope="row">Top Shadow</th>
                                <td>
                                    <label>
                                        <input
                                            type="checkbox"
                                            defaultChecked={state?.shadow}
                                            className="checkbox-switch"
                                            onChange={() => handle_field({ shadow: !state?.shadow })}
                                        />
                                    </label>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">Background Color</th>
                                <td><ColorPicker color={state?.background_color} onUpdate={(background_color) => handle_field({ background_color })} /></td>
                            </tr>

                            <tr>
                                <th scope="row">Rating Text Color</th>
                                <td><ColorPicker color={state?.rating_text_color} onUpdate={(rating_text_color) => handle_field({ rating_text_color })} /></td>
                            </tr>

                            <tr>
                                <th scope="row">Review Rating Background Color</th>
                                <td><ColorPicker color={state?.review_rating_background_color} onUpdate={(review_rating_background_color) => handle_field({ review_rating_background_color })} /></td>
                            </tr>

                            <tr>
                                <th scope="row">Number of Review Text Color</th>
                                <td><ColorPicker color={state?.number_review_text_color} onUpdate={(number_review_text_color) => handle_field({ number_review_text_color })} /></td>
                            </tr>
                        </tbody>
                    </table>

                    <h2 className="section-title-large">Call-to-action Button</h2>
                    <table className="form-table">
                        <caption>First Button</caption>
                        <tbody>
                            <tr>
                                <td colSpan={2} style={{ paddingLeft: 0 }}>
                                    <label>
                                        <input
                                            type="checkbox"
                                            defaultChecked={state?.button1?.show}
                                            className="checkbox-switch"
                                            onChange={() => handle_button('show', !state.button1?.show)}
                                        /> Second Button
                                    </label>
                                </td>
                            </tr>
                            {state?.button1?.show && <Button key={'button1'} onUpdate={handle_button} {...state?.button1} />}
                        </tbody>
                    </table>

                    <div className="gap-30" />
                    <table className="form-table">
                        <caption>Second Button</caption>
                        <tbody>
                            <tr>
                                <td colSpan={2} style={{ paddingLeft: 0 }}>
                                    <label>
                                        <input
                                            type="checkbox"
                                            defaultChecked={state?.button2?.show}
                                            className="checkbox-switch"
                                            onChange={() => handle_button2('show', !state.button2?.show)}
                                        /> Second Button
                                    </label>
                                </td>
                            </tr>

                            {state?.button2?.show && <Button key={'button2'} onUpdate={handle_button2} {...state?.button2} />}
                        </tbody>
                    </table>

                </div>

                <div className="column-page-selection">
                    <h2 className="section-title-large" style={{ marginTop: 0 }}>Page(s) to Show on</h2>
                    <Pages onUpdate={handle_field} on_pages={state?.on_pages} />
                </div>
            </div>
        </React.Fragment>
    );
};

export default CTABanner;
