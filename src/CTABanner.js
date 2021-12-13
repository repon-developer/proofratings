import store, { ACTIONS } from "./Store";
import ColorPicker from "./ColorPicker";

import Button from "./Button";
import Pages from "./Pages";

const { useState, useEffect } = React;

const CTABanner = () => {

    const [state, setState] = useState(store.getState().overall_cta_banner);

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setState(store.getState().overall_cta_banner));
        return () => unsubscribe();
    }, [])

    const handle_field = (data) => store.dispatch({ type: ACTIONS.OVERALL_CTA_BANNER, payload: data });

    const handle_button = (name, value) => {
        let button1 = typeof state.button1 === 'object' ? state.button1 : {};        
        button1[name] = value;
        handle_field({button1})
    }

    const handle_button2 = (name, value) => {
        let button2 = typeof state.button2 === 'object' ? state.button2 : {};        
        button2[name] = value;
        handle_field({button2})
    }

    const get_styles = () => {
        const styles = []
        if ( state?.star_color ) {
            styles.push('--star_color:' + state.star_color);
        }

        if ( state?.background_color ) {
            styles.push('--backgroundColor:' + state.background_color);
        }

        if ( state?.rating_text_color ) {
            styles.push('--rating_text_color:' + state.rating_text_color);
        }
    
        if ( state?.review_rating_background_color ) {
            styles.push('--review_rating_background_color:' + state.review_rating_background_color);
        }

        if ( state?.number_review_text_color ) {
            styles.push('--reviewCountTextcolor:' + state.number_review_text_color);
        }
    
        return styles;
    }

    const css_style = `.proofratings-banner-badge {${get_styles().join(';')}}`;
    
    return (
        <React.Fragment>
            <style>{css_style}</style>
            <table className="form-table">
                <tbody>
                    
                    <tr>
                        <th scope="row">Tablet Visibility</th>
                        <td>
                            <label>
                                <input
                                    type="checkbox"
                                    defaultChecked={state?.tablet}
                                    className="checkbox-switch"
                                    onChange={() => handle_field({tablet: !state?.tablet})}
                                />
                                Show/Hide on tablet
                            </label>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Mobile Visibility</th>
                        <td>
                            <label>
                                <input
                                    type="checkbox"
                                    defaultChecked={state?.mobile}
                                    className="checkbox-switch"
                                    onChange={() => handle_field({mobile: !state?.mobile})}
                                />
                                Show/Hide on mobile
                            </label>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Close option</th>
                        <td>
                            <label>
                                <input
                                    type="checkbox"
                                    defaultChecked={state?.close_button}
                                    className="checkbox-switch"
                                    onChange={() => handle_field({close_button: !state?.close_button})}
                                />
                            </label>
                        </td>
                    </tr>

                    <tr>
                        <td style={{ paddingLeft: 0 }} colSpan={2}>
                            <label>
                                <input
                                    type="checkbox"
                                    className="checkbox-switch"
                                    defaultChecked={state?.customize}
                                    onChange={() => handle_field({customize: !state?.customize})}
                                /> Customize
                            </label>
                        </td>
                    </tr>

                    {state?.customize && (
                        <React.Fragment>
                            <tr>
                                <td colSpan={2} style={{paddingLeft: 0}}>
                                    <div className={`proofratings-banner-badge badge-hidden-mobile ${state?.shadow ? 'has-shadow' : ''}`}>
                                        <div className="proofratings-logos">
                                            <img src={`${proofratings.assets_url}/images/icon-google.png`} alt="google"/>
                                            <img src={`${proofratings.assets_url}/images/icon-trustpilot.png`} alt="trustpilot"/>
                                            <img src={`${proofratings.assets_url}/images/icon-wordpress.jpg`} alt="wordpress"/>
                                        </div>
                                        <div className="rating-box">
                                            <span className="proofratings-stars medium">
                                                <i style={{ width: "96%" }} />
                                            </span>
                                            <span className="rating">4.8 / 5</span>
                                        </div>
                                        <div className="proofratings-review-count">44 customer reviews</div>
                                        <div className="button-container">
                                            <div className="proofratings-button button1 has-border">Buy Now</div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Star Color</th>
                                <td><ColorPicker color={state?.star_color} onUpdate={(star_color) => handle_field({star_color})} /></td>
                            </tr>

                            <tr>
                                <th scope="row">Top Shadow</th>
                                <td>
                                    <label>
                                        <input
                                            type="checkbox"
                                            defaultChecked={state?.shadow}
                                            className="checkbox-switch"
                                            onChange={() => handle_field({shadow: !state.shadow})}
                                        />
                                    </label>
                                </td>
                            </tr>
                        
                            <tr>
                                <th scope="row">Background Color</th>
                                <td><ColorPicker color={state?.background_color} onUpdate={(background_color) => handle_field({background_color})} /></td>
                            </tr>

                            <tr>
                                <th scope="row">Rating Text Color</th>
                                <td><ColorPicker color={state?.rating_text_color} onUpdate={(rating_text_color) => handle_field({rating_text_color})} /></td>
                            </tr>
                            
                            <tr>
                                <th scope="row">Review Rating Background Color</th>
                                <td><ColorPicker color={state?.review_rating_background_color} onUpdate={(review_rating_background_color) => handle_field({review_rating_background_color})} /></td>
                            </tr>

                            <tr>
                                <th scope="row">Number of Review Text Color</th>
                                <td><ColorPicker color={state?.number_review_text_color} onUpdate={(number_review_text_color) => handle_field({number_review_text_color})} /></td>
                            </tr>
                        </React.Fragment>
                    )}

                </tbody>
            </table>

            <h2 style={{fontSize: 25}}>Call-to-action Button</h2>
            <table className="form-table">
                <caption>First Button</caption>
                <tbody>                    
                    <Button key={'button1'} onUpdate={handle_button} {...state?.button1}  />
                </tbody>
            </table>

            <div className="gap-30" />
            <table className="form-table">
                <caption>Second Button</caption>
                <tbody>                    
                    <tr>
                        <td colSpan={2} style={{paddingLeft: 0}}>
                            <label>
                                <input
                                    type="checkbox"
                                    defaultChecked={state?.button2}
                                    className="checkbox-switch"
                                    onChange={() => handle_button2('show', !state.button2?.show)}
                                /> Second Button
                            </label>
                        </td>
                    </tr>

                    {state?.button2?.show && <Button key={'button2'} onUpdate={handle_button2} {...state?.button2}  />}
                </tbody>
            </table>

            <Pages onUpdate={handle_field} hide_on={state?.hide_on} />

        </React.Fragment>
    );
};

export default CTABanner;
