import store, { ACTIONS } from "./Store";
import ColorPicker from "./Component/ColorPicker";

const { useState, useEffect } = React;

const OverallPopup = () => {
    const [state, setState] = useState(store.getState().overall_popup);

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setState(store.getState().overall_popup));
        return () => unsubscribe();
    }, [])

    const handle_field = (data) => store.dispatch({ type: ACTIONS.OVERALL_POPUP, payload: data });

    const get_styles = () => {
        const styles = []
        if ( state?.star_color ) {
            styles.push('--themeColor:' + state.star_color);
        }

        if ( state?.logo_color ) {
            styles.push('--logoColor:' + state.logo_color);
        }

        if ( state?.review_text_color ) {
            styles.push('--reviewCountTextColor:' + state.review_text_color);
        }

        if ( state?.review_text_background ) {
            styles.push('--review_text_background:' + state.review_text_background);
        }
    
        if ( state?.rating_color ) {
            styles.push('--rating_color:' + state.rating_color);
        }

        if ( state?.view_review_color ) {
            styles.push('--view_review_color:' + state.view_review_color);
        }
    
        return styles;
    }

    const css_style = `.proofratings-popup-widgets-box .proofratings-widget{${get_styles().join(';')}}`;

    let widget_class = 'proofratings-widget proofratings-widget-customized';
    if ( state?.logo_color ) {
        widget_class += ' proofratings-widget-logo-color';
    }

    return (
        <React.Fragment>
            <style>{css_style}</style>
            <table className="form-table">
                <tbody>
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
                                    <div className="proofratings-badges-popup">
                                        <div className="proofratings-popup-widgets-box" data-column={3}>
                                            <div className={widget_class}>
                                                <div className="review-site-logo" style={{'WebkitMaskImage': `url(${proofratings.assets_url}/images/google.svg)`}}>
                                                    <img src={`${proofratings.assets_url}/images/google.svg`} alt="Google"/>
                                                </div>
                                                <div className="proofratings-reviews" itemProp="reviewRating">
                                                    <span className="proofratings-score">5.0</span>
                                                    <span className="proofratings-stars">
                                                        <i style={{ width: "100%" }} />
                                                    </span>
                                                </div>
                                                <div className="review-count"> 9 reviews </div>
                                                <p className="view-reviews">View Reviews</p>
                                            </div>

                                            <div className={widget_class}>
                                                <div className="review-site-logo" style={{WebkitMaskImage: `url(${proofratings.assets_url}/images/wordpress.svg)`}}>
                                                    <img src={`${proofratings.assets_url}/images/wordpress.svg`} alt="Wordpress"/>
                                                </div>
                                                <div className="proofratings-reviews" itemProp="reviewRating">
                                                    <span className="proofratings-score">5.0</span>
                                                    <span className="proofratings-stars">
                                                        <i style={{ width: "100%" }} />
                                                    </span>
                                                </div>
                                                <div className="review-count"> 25 reviews </div>
                                                <p className="view-reviews">View Reviews</p>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">Star Color</th>
                                <td><ColorPicker color={state?.star_color} onUpdate={(star_color) => handle_field({star_color})} /></td>
                            </tr>

                            <tr>
                                <th scope="row">Review Text Color</th>
                                <td><ColorPicker color={state?.review_text_color} onUpdate={(review_text_color) => handle_field({review_text_color})} /></td>
                            </tr>
                        
                            <tr>
                                <th scope="row">Review Background Color</th>
                                <td><ColorPicker color={state?.review_text_background} onUpdate={(review_text_background) => handle_field({review_text_background})} /></td>
                            </tr>
                            
                            <tr>
                                <th scope="row">Rating Color</th>
                                <td><ColorPicker color={state?.rating_color} onUpdate={(rating_color) => handle_field({rating_color})} /></td>
                            </tr>

                            <tr>
                                <th scope="row">View Review Color</th>
                                <td><ColorPicker color={state?.view_review_color} onUpdate={(view_review_color) => handle_field({view_review_color})} /></td>
                            </tr>
                        </React.Fragment>
                    )}

                </tbody>
            </table>
        </React.Fragment>
    );
};

export default OverallPopup;
