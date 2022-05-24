import store, { ACTIONS } from './Store';
import ColorPicker from "./Component/ColorPicker";
import ActiveSites from './Component/ActiveSites';

const { useState, useEffect } = React;

const Sites_Icon = (props) => {
    const [state, setState] = useState(store.getState().widget_icon)

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setState(store.getState().widget_icon))
        return () => unsubscribe();
    }, [])

    const handle_field = (data) => store.dispatch({ type: ACTIONS.WIDGET_ICON, payload: data })

    const get_styles = () => {
        const styles = []
        if (state?.star_color) {
            styles.push('--themeColor:' + state.star_color);
        }

        if (state?.icon_color) {
            styles.push('--logoColor:' + state.icon_color);
        }

        if (state?.textcolor) {
            styles.push('--textcolor:' + state.textcolor);
        }


        return styles;
    }

    let css_style = `.proofratings-widget.proofratings-widget-icon {${get_styles().join(';')}}`;

    let widget_classes = 'proofratings-widget proofratings-widget-icon proofratings-widget-customized';
    if (state?.icon_color) {
        widget_classes += ' proofratings-widget-logo-color';
    }

    return (
        <React.Fragment>
            <style>{css_style}</style>

            <div className="proofratings-copyarea">
                <h3>Shortcode</h3>
                <code className="shortocde-area">[proofratings_widgets id="{props?.id}" style="icon"]</code>
                <p className="description">
                    Copy and paste this shortcode where you want to display the review badge. <br />
                    Note: Number of badges in a row is responsive and adjusts automatically to the space available
                </p>
            </div>

            <div className='gap-30' />

            <ActiveSites onUpdate={(widget_connections) => handle_field({ widget_connections })} widget_connections={state?.widget_connections} />

            <div className="proofratings-review-widgets-grid proofratings-widget-grid-icon" style={{ padding: '10px 15px', backgroundColor: '#fff' }}>
                <div className={widget_classes}>
                    <div className="review-site-logo" style={{ WebkitMaskImage: `url(${proofratings.assets_url}images/icon3-yelp.svg)` }}>
                        <img src={`${proofratings.assets_url}images/icon3-yelp.svg`} alt="Yelp" />
                    </div>

                    <div className="review-info-container">
                        <span className="proofratings-stars"><i style={{ width: '95.6%' }}></i></span>
                        <div className="review-info">
                            <span className="proofratings-rating">4.8 Rating</span>
                            <span className="separator-circle">●</span>
                            <span className="proofratings-review-number">41 Reviews</span>
                        </div>
                    </div>
                </div>
            </div>


            <table className="form-table">
                <tbody>
                    <tr>
                        <th scope="row">Icon Color</th>
                        <td><ColorPicker color={state?.icon_color} onUpdate={(icon_color) => handle_field({ icon_color })} /></td>
                    </tr>

                    <tr>
                        <th scope="row">Star Color</th>
                        <td><ColorPicker color={state?.star_color} onUpdate={(star_color) => handle_field({ star_color })} /></td>
                    </tr>

                    <tr>
                        <th scope="row">Text color</th>
                        <td><ColorPicker color={state?.textcolor} onUpdate={(textcolor) => handle_field({ textcolor })} /></td>
                    </tr>

                    <tr>
                        <th scope="row">Position</th>
                        <td>
                            <select defaultValue={state?.position} onChange={(e) => handle_field({ position: e.target.value })}>
                                <option value="horizontal">Horizontal</option>
                                <option value="vertical">Vertical</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </React.Fragment>
    );
};

export default Sites_Icon;
