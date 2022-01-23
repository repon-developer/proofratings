import store, { ACTIONS } from './Store';
import ColorPicker from "./Component/ColorPicker";
import Border from "./Component/Border";
import Shadow from "./Component/Shadow";

import ActiveSites from './Component/ActiveSites';

const { useState, useEffect } = React;

const BadgeBasic = (props) => {
    const [state, setState] = useState(store.getState().badge_basic)
    
    useEffect(() => {
        const unsubscribe = store.subscribe(() => setState(store.getState().badge_basic))
        return () => unsubscribe();
    }, [])

    const handle_field = (data) => store.dispatch({ type: ACTIONS.BADGE_BASIC, payload: data})

    const get_styles = () => {
        const styles = []
        if ( state?.star_color ) {
            styles.push('--themeColor:' + state.star_color);
        }

        if ( state?.logo_color ) {
            styles.push('--logoColor:' + state.logo_color);
        }
    
        if ( state?.textcolor ) {
            styles.push('--textColor:' + state.textcolor);
        }

        if ( state?.review_color_textcolor ) {
            styles.push('--reviewCountTextColor:' + state.review_color_textcolor);
        }

        if ( state?.background_color ) {
            styles.push('background-color:' + state.background_color);
        }
    
        return styles;
    }
    
    let css_style = `.proofratings-widget.proofratings-widget-basic {${get_styles().join(';')}}`;

    return (
        <React.Fragment>
            <style>{css_style}</style>

            <ActiveSites onUpdate={(active_sites) => handle_field({active_sites})} active_sites={state?.active_sites} />

            <table className="form-table">
                <tbody>
                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>
                            Shortcode
                            <p className="description" style={{ fontWeight: "normal" }}>Use shortcode where you want to display review widgets</p>
                        </th>
                        <td>
                            <code className="shortocde-area">[proofratings_widgets id="{props?.id}" style="basic"]</code>
                        </td>
                    </tr>
                </tbody>
            </table>
            <label>
                <input
                    type="checkbox"
                    checked={state?.customize}
                    className="checkbox-switch checkbox-yesno"
                    onChange={() => handle_field({customize: !state?.customize})}
                />
                Customize (this will customize all badges)
            </label>

            {state?.customize && <div className="gap-30" />}

            {state?.customize && (
                <React.Fragment>
                    <div className="proofratings-review-widgets-grid">
                        <div className={`proofratings-widget proofratings-widget-basic proofratings-widget-customized ${state?.logo_color ? 'proofratings-widget-logo-color' : ''}`}>
                            <div className="review-site-logo" style={{WebkitMaskImage: `url(${proofratings.assets_url}images/google.svg)`}}>
                                <img src={`${proofratings.assets_url}images/google.svg`} alt="Google" />
                            </div>
                            
                            <div className="proofratings-stars"><i style={{width: '80%'}} /></div>
                            
                            <div className="review-count">76 user rating</div>
                            <a className="view-reviews" href="#">View all reviews</a>
                        </div>
                    </div>


                    <table className="form-table">
                        <tbody>
                            <tr>
                                <th scope="row">Logo Color</th>
                                <td><ColorPicker color={state?.logo_color} onUpdate={(logo_color) => handle_field({logo_color})} /></td>
                            </tr>

                            <tr>
                                <th scope="row">Star Color</th>
                                <td><ColorPicker color={state?.star_color} onUpdate={(star_color) => handle_field({star_color})} /></td>
                            </tr>

                            <tr>
                                <th scope="row">Review Count Color</th>
                                <td><ColorPicker color={state?.review_count_color} onUpdate={(review_count_color) => handle_field({review_count_color})} /></td>
                            </tr>

                            <tr>
                                <th scope="row">View Reviews text color</th>
                                <td><ColorPicker color={state?.view_reviews_text_color} onUpdate={(view_reviews_text_color) => handle_field({view_reviews_text_color})} /></td>
                            </tr>
                        </tbody>
                    </table>
                </React.Fragment>
            )}
        </React.Fragment>
    );
};

export default BadgeBasic;
