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

        if ( state?.icon_color ) {
            styles.push('--logoColor:' + state.icon_color);
        }
    
        if ( state?.textcolor ) {
            styles.push('--textcolor:' + state.textcolor);
        }

    
        return styles;
    }
    
    let css_style = `.proofratings-widget.proofratings-widget-icon {${get_styles().join(';')}}`;

    let widget_classes = 'proofratings-widget proofratings-widget-icon proofratings-widget-customized';
    if ( state?.icon_color ) {
        widget_classes += ' proofratings-widget-logo-color';
    }

    if ( state?.position ) {
        widget_classes += ' proofratings-widget-'+state.position;
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
                    <div className="proofratings-review-widgets-grid proofratings-widget-grid-icon" style={{padding: '10px 15px', backgroundColor: '#fff'}}>
                        <div className={widget_classes}>
                            <div className="review-site-logo" style={{WebkitMaskImage: `url(${proofratings.assets_url}images/icon3-yelp.svg)`}}>
                                <img src={`${proofratings.assets_url}images/icon3-yelp.svg`} alt="Yelp" />
                            </div>

                            <div class="review-info-container">
                                <span className="proofratings-stars"><i style={{width: '95.6%'}}></i></span>
                                <div className="review-info">
                                    <span className="proofratings-rating">Rated 4.8</span>
                                    <span className="separator-circle">●</span>
                                    <span className="proofratings-review-number">41 reviews</span>
                                </div>
                            </div>
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
