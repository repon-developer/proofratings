import store, { ACTIONS, get_proofrating, get_settings, get_connections } from './Store';
import ColorPicker from "./../Component/ColorPicker";
import Border from "./../Component/Border";
import Shadow from "./../Component/Shadow";
import ActiveSites from './../Component/ActiveSites';

const { useState, useEffect } = React;

const BadgeSquare = (props) => {
    const [state, setState] = useState( get_settings().widget_square)

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setState(get_settings().widget_square))
        return () => unsubscribe();
    }, [])

    const handle_field = (data) => store.dispatch({ type: ACTIONS.WIDGET_SQUARE, payload: data })

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

        if (state?.logo_color) {
            styles.push('--logoColor:' + state.logo_color);
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

    let css_style = `.proofratings-widget.proofratings-widget-square {${get_styles().join(';')}}`;
    if (shadow?.shadow !== false && shadow?.hover) {
        css_style += `.proofratings-widget.proofratings-widget-square:hover {--shadowColor: ${shadow.hover}}`;
    }

    const get_widget = () => {
        const connection = Object.assign({logo: `${get_proofrating().assets_url}images/google.svg`, reviews: '#'}, get_connections(true)[0]);

        return (
            <React.Fragment>
                <h2 className="section-title-large">Color Selection</h2>
                <div id="proofratings-badge-square" className="proofratings-review-widgets-grid proofratings-widgets-grid-square">
                    <div className="proofratings-widget proofratings-widget-square">
                        <div className="review-site-logo">
                            <img src={connection.logo} alt="Google" />
                        </div>

                        <div className="proofratings-reviews" itemProp="reviewRating">
                            <span className="proofratings-score">5.0</span>
                            <span className="proofratings-stars"><i style={{ width: '100%' }} /></span>
                        </div>

                        <div className="review-count"> {connection.reviews} reviews </div>
                        <p className="view-reviews">View Reviews</p>
                    </div>
                </div>
            </React.Fragment>
        )
    }

    return (
        <React.Fragment>
            <style>{css_style}</style>

            <div className="proofratings-copyarea">
                <h3>Shortcode</h3>
                <code className="shortocde-area">[proofratings_widgets id="{props?.id}" style="square"]</code>
                <p className="description">
                    Copy and paste this shortcode where you want to display the review badge. <br />
                    Note: Number of badges in a row is responsive and adjusts automatically to the space available
                </p>
            </div>

            <ActiveSites onUpdate={(widget_connections) => handle_field({ widget_connections })} widget_connections={state?.widget_connections} />

            {get_widget()}

            <table className="form-table">
                <tbody>
                    <tr>
                        <th scope="row">Star Color</th>
                        <td><ColorPicker color={state?.star_color} onUpdate={(star_color) => handle_field({ star_color })} /></td>
                    </tr>
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

export default BadgeSquare;
