import store, { ACTIONS } from "./Store";
import ColorPicker from "./ColorPicker";
import Border from "./Border";
import Shadow from "./Shadow";

import Pages from "./Pages";

const { useState, useEffect } = React;

const OverallRectangle_Embed = (props) => {
    const settings = store.getState();

    const [state, setState] = useState(store.getState().overall_rectangle);

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setState(store.getState().overall_rectangle));
        return () => unsubscribe();
    }, [])

    const handle_field = (data) => store.dispatch({ type: ACTIONS.OVERALL_RECTANGLE, payload: data });

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

        if ( state?.review_background ) {
            styles.push('--review_background:' + state.review_background);
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

    css_style = `.proofratings-badge.proofratings-badge-rectangle {${get_styles().join(';')}}`;

    return (
        <React.Fragment>
            <style>{css_style}</style>
            <table className="form-table">
                <tbody>
                    <tr>
                        <th scope="row">
                            Shortcode
                            <p className="description" style={{ fontWeight: "normal" }}>Embed shortcode</p>
                        </th>
                        <td>
                            <code className="shortocde-area">
                                [proofratings_overall_ratings id="{props?.id}" type="rectangle"]
                            </code>
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
                                    <div className="proofratings-badge proofratings-badge-rectangle">
                                        <div className="proofratings-inner">
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

                            <Shadow shadow={shadow} onUpdate={handleShadow} />
                        
                            <tr>
                                <th scope="row">Background Color</th>
                                <td><ColorPicker color={state?.background_color} onUpdate={(background_color) => handle_field({background_color})} /></td>
                            </tr>
                            <tr>
                                <th scope="row">Review Text Color</th>
                                <td><ColorPicker color={state?.review_text_color} onUpdate={(review_text_color) => handle_field({review_text_color})} /></td>
                            </tr>
                            <tr>
                                <th scope="row">Review Background Color</th>
                                <td><ColorPicker color={state?.review_background} onUpdate={(review_background) => handle_field({review_background})} /></td>
                            </tr>
                        </React.Fragment>
                    )}

                </tbody>
            </table>

        </React.Fragment>
    );
};

export default OverallRectangle_Embed;
