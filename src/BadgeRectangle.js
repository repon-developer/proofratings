import store, { ACTIONS } from './Store';
import ColorPicker from "./ColorPicker";
import Border from "./Border";
import Shadow from "./Shadow";

import ActiveSites from './Component/ActiveSites';

const { useState, useEffect } = React;

const BadgeRectangle = (props) => {
    const [state, setState] = useState(store.getState().sites_rectangle)

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setState(store.getState().sites_rectangle))
        return () => unsubscribe();
    }, [])
    

    const handle_field = (data) => store.dispatch({ type: ACTIONS.SITES_RECTANGLE, payload: data})

    const border = Object.assign({ show: false, color: "", hover: "" }, state?.border)
    const handleBorder = (name, value) => {
        border[name] = value;
        handle_field({border})
    }

    const shadow = Object.assign({ shadow: false, color: "", hover: "" }, state?.shadow)
    const handleShadow = (name, value) => {
        shadow[name] = value;
        handle_field({shadow})
    }

    const get_styles = () => {
        const styles = []
        if ( state?.star_color ) {
            styles.push('--themeColor:' + state.star_color);
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
    
        if ( state?.border?.show === false ) {
            styles.push('border: none');
        }
    
        if ( border?.color ) {
            styles.push('--borderColor:' + border.color);
        }
    
        if ( border?.hover ) {
            styles.push('--borderHoverColor:' + border.hover);
        }

        if ( shadow?.shadow === false ) {
            styles.push('--shadowColor: transparent');
        }

        if ( shadow?.shadow !== false && shadow?.color ) {
            styles.push('--shadowColor:' + shadow.color);
        }
    
        return styles;
    }

    
    css_style = `.proofratings-widget.proofratings-widget-rectangle {${get_styles().join(';')}}`;
    if ( shadow?.shadow !== false && shadow?.hover ) {
        css_style += `.proofratings-widget.proofratings-widget-rectangle:hover {--shadowColor: ${shadow.hover}}`;
    }

    if ( state?.icon_color ) {
        css_style += `.proofratings-widget.proofratings-widget-rectangle .review-site-logo svg {fill: ${state.icon_color}}`;
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
                            <code className="shortocde-area">[proofratings_widgets id="{props?.id}" style="rectangle"]</code>
                        </td>
                    </tr>
                </tbody>
            </table>
            <label>
                <input
                    type="checkbox"
                    defaultChecked={state?.customize}
                    className="checkbox-switch checkbox-yesno"
                    onChange={() => handle_field({customize: !state?.customize})}
                />
                Customize (this will customize all badges)
            </label>

            {state?.customize && <div className="gap-30" />}

            {state?.customize && (
                <React.Fragment>
                    <div id="proofratings-badge-rectangle" className="proofratings-review-widgets-grid proofratings-widgets-grid-rectangle">
                        <div className="proofratings-widget proofratings-widget-rectangle proofratings-widget-yelp proofratings-widget-customized">
                            <div className="review-site-logo" dangerouslySetInnerHTML={{__html: '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1000 1000" style="enable-background:new 0 0 1000 1000;" xml:space="preserve"><style type="text/css">.st0{fill:#FFFFFF;}</style><circle cx="500" cy="500" r="493"></circle><path class="st0" d="M283.7,500.98c0-123.13,100.17-223.3,223.3-223.3c49.73,0,96.79,16,136.11,46.27l-51.89,67.41 c-24.31-18.71-53.44-28.61-84.22-28.61c-76.22,0-138.23,62.01-138.23,138.23S430.78,639.21,507,639.21 c61.39,0,113.56-40.22,131.54-95.7H507v-85.06h223.3v42.53c0,123.13-100.17,223.3-223.3,223.3S283.7,624.1,283.7,500.98z"></path></svg>'}}></div>
                            <h4 className="rating-title">Google Rating</h4>
                            <div className="proofratings-reviews" itemProp="reviewRating">
                                <span className="proofratings-score">5.0</span>
                                <span className="proofratings-stars">
                                    <i style={{ width: "100%" }} />
                                </span>
                            </div>
                            <div className="review-count"> 9 reviews </div>
                        </div>
                    </div>


                    <table className="form-table">
                        <tbody>
                            <tr>
                                <th scope="row">Star Color</th>
                                <td><ColorPicker color={state?.star_color} onUpdate={(star_color) => handle_field({star_color})} /></td>
                            </tr>

                            <tr>
                                <th scope="row">Site Icon Color</th>
                                <td><ColorPicker color={state?.icon_color} onUpdate={(icon_color) => handle_field({icon_color})} /></td>
                            </tr>

                            <tr>
                                <th scope="row">Text Color</th>
                                <td><ColorPicker color={state?.textcolor} onUpdate={(textcolor) => handle_field({textcolor})} /></td>
                            </tr>
                            <tr>
                                <th scope="row">Review count text color</th>
                                <td><ColorPicker color={state?.review_color_textcolor} onUpdate={(review_color_textcolor) => handle_field({review_color_textcolor})} /></td>
                            </tr>
                            <tr>
                                <th scope="row">Background Color</th>
                                <td><ColorPicker color={state?.background_color} onUpdate={(background_color) => handle_field({background_color})} /></td>
                            </tr>

                            <Border name="border" border={border} onUpdate={handleBorder} />
                            <Shadow shadow={shadow} onUpdate={handleShadow} />
                        </tbody>
                    </table>
                </React.Fragment>
            )}
        </React.Fragment>
    );
};

export default BadgeRectangle;
