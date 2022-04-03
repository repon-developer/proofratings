import store, { ACTIONS } from './Store';
import ColorPicker from "./Component/ColorPicker";
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
    
        if ( state?.review_count_textcolor ) {
            styles.push('--review_count_textcolor:' + state.review_count_textcolor);
        }

        if ( state?.view_reviews_text_color ) {
            styles.push('--view_review_textcolor:' + state.view_reviews_text_color);
        }
    
        return styles;
    }
    
    let css_style = `.proofratings-widget.proofratings-widget-basic {${get_styles().join(';')}}`;

    let widget_classes = 'proofratings-widget proofratings-widget-basic proofratings-widget-customized';
    if ( state?.logo_color ) {
        widget_classes += ' proofratings-widget-logo-color';
    }

    if ( state?.alignment ) {
        widget_classes += ' proofratings-widgets-align-'+state.alignment;
    }

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
                            <p className="description">Number of badges in a row adjusts automatically to the space available.</p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Alignment</th>
                        <td>
                            <select defaultValue={state?.alignment} onChange={(e) => handle_field({alignment: e.target.value})}>
                                <option value="left">Left</option>
                                <option value="center">Center</option>
                                <option value="right">Right</option>
                            </select>
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
                    <div className="proofratings-review-widgets-grid proofratings-widget-grid-basic">
                        <div className={widget_classes}>
                            <div className="review-site-logo" style={{WebkitMaskImage: `url(${proofratings.assets_url}images/google.svg)`}}>
                                <img src={`${proofratings.assets_url}images/google.svg`} alt="Google" />
                            </div>
                            
                            <div className="proofratings-stars"><i style={{width: '80%'}} /></div>
                            
                            <div className="review-count">76 ratings</div>
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
                                <td><ColorPicker color={state?.review_count_textcolor} onUpdate={(review_count_textcolor) => handle_field({review_count_textcolor})} /></td>
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
