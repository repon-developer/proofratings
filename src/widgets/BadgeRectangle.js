import store, { ACTIONS, get_settings } from '../widgets/Store';
import { get_proofratings } from "../global";
import ColorPicker from "./../Component/ColorPicker";
import Border from "./../Component/Border";
import Shadow from "./../Component/Shadow";
import ActiveSites from './../Component/ActiveSites'

const { useState, useEffect } = React;

const BadgeRectangle = (props) => {
    const [state, setState] = useState(get_settings().widget_rectangle)

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setState(get_settings().widget_rectangle))
        return () => unsubscribe();
    }, [])


    const handle_field = (data) => store.dispatch({ type: ACTIONS.WIDGET_RECTANGLE, payload: data })

    const border = Object.assign({ show: false, color: "", hover: "" }, state?.border)
    const handleBorder = (name, value) => {
        border[name] = value;
        handle_field({ border })
    }

    const shadow = Object.assign({ shadow: false, color: "", hover: "" }, state?.shadow)
    const handleShadow = (name, value) => {
        shadow[name] = value;
        handle_field({ shadow })
    }

    const get_styles = () => {
        const styles = []
        if (state?.star_color) {
            styles.push('--themeColor:' + state.star_color);
        }

        if (state?.textcolor) {
            styles.push('--textColor:' + state.textcolor);
        }

        if (state?.review_color_textcolor) {
            styles.push('--reviewCountTextColor:' + state.review_color_textcolor);
        }

        if (state?.background_color) {
            styles.push('background-color:' + state.background_color);
        }

        if (state?.border?.show === false) {
            styles.push('border: none');
        }

        if (border?.color) {
            styles.push('--borderColor:' + border.color);
        }

        if (border?.hover) {
            styles.push('--borderHoverColor:' + border.hover);
        }

        if (shadow?.shadow === false) {
            styles.push('--shadowColor: transparent');
        }

        if (shadow?.shadow !== false && shadow?.color) {
            styles.push('--shadowColor:' + shadow.color);
        }

        return styles;
    }

    let css_style = `.proofratings-widget.proofratings-widget-rectangle {${get_styles().join(';')}}`;
    if (shadow?.shadow !== false && shadow?.hover) {
        css_style += `.proofratings-widget.proofratings-widget-rectangle:hover {--shadowColor: ${shadow.hover}}`;
    }

    if (state?.icon_color) {
        css_style += `.proofratings-widget.proofratings-widget-rectangle .review-site-logo svg {fill: ${state.icon_color}}`;
    }

    return (
        <React.Fragment>
            <style>{css_style}</style>

            <div className="proofratings-copyarea">
                <h3>Shortcode</h3>
                <code className="shortocde-area">[proofratings_widgets id="{props?.id}" style="rectangle"]</code>
                <p className="description">
                    Copy and paste this shortcode where you want to display the review badge. <br />
                    Note: Number of badges in a row is responsive and adjusts automatically to the space available
                </p>
            </div>

            <ActiveSites onUpdate={(widget_connections) => handle_field({ widget_connections })} widget_connections={state?.widget_connections} />

            <div id={`proofratings-widgets-${props?.id}`} className="proofratings-review-widgets-grid proofratings-widgets-grid-rectangle">
                <div className="proofratings-widget proofratings-widget-rectangle proofratings-widget-yelp proofratings-widget-customized">
                    <div className="review-site-logo"><img src={`${get_proofratings().assets_url}/images/energysage.svg`} alt="Energy Sage" /></div>

                    <div className="proofratings-reviews" itemProp="reviewRating">
                        <span className="proofratings-score">5.0</span>
                        <span className="proofratings-stars">
                            <i style={{ width: "100%" }} />
                        </span>
                    </div>
                    <div className="review-count"> # reviews </div>
                </div>
            </div>


            <table className="form-table">
                <tbody>
                    <tr>
                        <th scope="row">Star Color</th>
                        <td><ColorPicker color={state?.star_color} onUpdate={(star_color) => handle_field({ star_color })} /></td>
                    </tr>

                    {/* <tr>
                        <th scope="row">Site Icon Color</th>
                        <td><ColorPicker color={state?.icon_color} onUpdate={(icon_color) => handle_field({ icon_color })} /></td>
                    </tr> */}

                    <tr>
                        <th scope="row">Text Color</th>
                        <td><ColorPicker color={state?.textcolor} onUpdate={(textcolor) => handle_field({ textcolor })} /></td>
                    </tr>
                    <tr>
                        <th scope="row">Review count text color</th>
                        <td><ColorPicker color={state?.review_color_textcolor} onUpdate={(review_color_textcolor) => handle_field({ review_color_textcolor })} /></td>
                    </tr>
                    <tr>
                        <th scope="row">Background Color</th>
                        <td><ColorPicker color={state?.background_color} onUpdate={(background_color) => handle_field({ background_color })} /></td>
                    </tr>

                    <Border name="border" border={border} onUpdate={handleBorder} />
                    <Shadow shadow={shadow} onUpdate={handleShadow} />
                </tbody>
            </table>
        </React.Fragment>
    );
};

export default BadgeRectangle;
