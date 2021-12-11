import store, { ACTIONS } from "./Store";
import ColorPicker from "./ColorPicker";
import Border from "./Border";
import Shadow from "./Shadow";

import Pages from "./Pages";

const { useState, useEffect } = React;

const OverallNarrow = () => {
    const settings = store.getState();

    const [state, setState] = useState(store.getState().overall_narrow);

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setState(store.getState().overall_narrow));
        return () => unsubscribe();
    }, [])

    const handle_field = (data) => store.dispatch({ type: ACTIONS.OVERALL_NARROW, payload: data });

    const shadow = Object.assign({ shadow: false, color: "", hover: "" }, state?.shadow)
    const handleShadow = (name, value) => {
        shadow[name] = value;
        handle_field({shadow})
    }

    const get_styles = () => {
        const styles = []
        if ( state?.star_color ) {
            styles.push('--star_color:' + state.star_color);
        }
    
        if ( state?.rating_color ) {
            styles.push('--rating_color:' + state.rating_color);
        }

        if ( state?.background_color ) {
            styles.push('--background_color:' + state.background_color);
        }

        if ( state?.review_text_color ) {
            styles.push('--review_text_color:' + state.review_text_color);
        }

        if ( shadow?.shadow === false ) {
            styles.push('--shadow_color: transparent');
            styles.push('--shadow_hover: transparent');
        }

        if ( shadow?.shadow !== false && shadow?.color ) {
            styles.push('--shadow_color:' + shadow.color);
        }

        if ( shadow?.shadow !== false && shadow?.hover ) {
            styles.push('--shadow_hover:' + shadow.hover);
        }
    
        return styles;
    }

    css_style = `.proofratings-badge.proofratings-badge-narrow {${get_styles().join(';')}}`;

    return (
        <React.Fragment>
            <style>{css_style}</style>
            <table className="form-table">
                <tbody>
                    <tr>
                        <th scope="row">
                            Shortcode <p className="description" style={{ fontWeight: "normal" }}>Embed shortcode</p>
                        </th>
                        <td><code className="shortocde-area">[proofratings_overall_ratings type="narrow"]</code></td>
                    </tr>

                    {settings?.badge_display?.overall_rectangle_float && (
                        <React.Fragment>
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
                                            onChange={() => handle_field({close_button: !state.close_button})}
                                        />
                                    </label>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">Position</th>
                                <td>
                                    <select defaultValue={state?.position} onChange={(e) => handle_field({position: e.target.value})}>
                                        <option value="left">Left</option>
                                        <option value="center">Center</option>
                                        <option value="right">Right</option>
                                    </select>
                                </td>
                            </tr>
                        </React.Fragment>
                    )}

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
                                <td style={{paddingLeft: 0}} colSpan={2}>
                                    <div className="proofratings-badge proofratings-badge-narrow">
                                        <div className="proofratings-logos">
                                            <img src={`${proofratings.assets_url}/images/icon-google.png`} alt="google"/>
                                            <img src={`${proofratings.assets_url}/images/icon-trustpilot.png`} alt="trustpilot"/>
                                            <img src={`${proofratings.assets_url}/images/icon-wordpress.jpg`} alt="wordpress"/>
                                        </div>
                                        <div className="proofratings-reviews">
                                            <span className="proofratings-score">4.8</span>
                                            <span className="proofratings-stars">
                                                <i style={{ width: "96%" }} />
                                            </span>
                                        </div>
                                        <div className="proofratings-review-count">44 reviews</div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">Star Color</th>
                                <td><ColorPicker color={state?.star_color} onUpdate={(star_color) => handle_field({star_color})} /></td>
                            </tr>

                            <tr>
                                <th scope="row">Rating Color</th>
                                <td><ColorPicker color={state?.rating_color} onUpdate={(rating_color) => handle_field({rating_color})} /></td>
                            </tr>

                            <Shadow shadow={state?.shadow} onUpdate={handleShadow} />
                        
                            <tr>
                                <th scope="row">Background Color</th>
                                <td><ColorPicker color={state?.background_color} onUpdate={(background_color) => handle_field({background_color})} /></td>
                            </tr>
                            
                            <tr>
                                <th scope="row">Review Count Text Color</th>
                                <td><ColorPicker color={state?.review_text_color} onUpdate={(review_text_color) => handle_field({review_text_color})} /></td>
                            </tr>
                        </React.Fragment>
                    )}

                </tbody>
            </table>

            {settings?.badge_display?.overall_narrow?.float && <Pages onUpdate={handle_field} hide_on={state?.hide_on} />}

        </React.Fragment>
    );
};

export default OverallNarrow;
