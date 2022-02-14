import store, { ACTIONS } from './Store';
import ColorPicker from "./Component/ColorPicker";
import ActiveSites from './Component/ActiveSites';

const { useState, useEffect } = React;

const Sites_Icon = (props) => {
    const [state, setState] = useState(store.getState().sites_icon)
    
    useEffect(() => {
        const unsubscribe = store.subscribe(() => setState(store.getState().sites_icon))
        return () => unsubscribe();
    }, [])

    const handle_field = (data) => store.dispatch({ type: ACTIONS.SITES_ICON, payload: data})

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
                            <code className="shortocde-area">[proofratings_widgets id="{props?.id}" style="icon"]</code>
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
                                <th scope="row">Icon Color</th>
                                <td><ColorPicker color={state?.icon_color} onUpdate={(icon_color) => handle_field({icon_color})} /></td>
                            </tr>

                            <tr>
                                <th scope="row">Star Color</th>
                                <td><ColorPicker color={state?.star_color} onUpdate={(star_color) => handle_field({star_color})} /></td>
                            </tr>

                            <tr>
                                <th scope="row">Text color</th>
                                <td><ColorPicker color={state?.textcolor} onUpdate={(textcolor) => handle_field({textcolor})} /></td>
                            </tr>

                            <tr>
                                <th scope="row">Position</th>
                                <td>
                                    <select defaultValue={state?.position} onChange={(e) => handle_field({position: e.target.value})}>
                                        <option value="horizontal">Horizontal</option>
                                        <option value="vertical">Vertical</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </React.Fragment>
            )}
        </React.Fragment>
    );
};

export default Sites_Icon;
