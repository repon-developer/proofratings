import {  get_connections } from '../widgets/Store';
import { get_proofratings } from "../global";

const WidgetSquare = (props) => {

    const border = Object.assign({ show: false, color: "", hover: "" }, props?.border)
    const shadow = Object.assign({ shadow: false, color: "", hover: "" }, props?.shadow)

    const get_styles = () => {
        const styles = []
        if (props?.star_color) {
            styles.push('--themeColor:' + props.star_color);
        }

        if (props?.logo_color) {
            styles.push('--logoColor:' + props.logo_color);
        }

        if (props?.textcolor) {
            styles.push('--textColor:' + props.textcolor);
        }

        if (props?.review_color_textcolor) {
            styles.push('--reviewCountTextColor:' + props.review_color_textcolor);
        }

        if (props?.background_color) {
            styles.push('background-color:' + props.background_color);
        }

        if (props?.border?.show === false) {
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

    const connection = Object.assign({logo: `${get_proofratings().assets_url}images/google.svg`, reviews: '#'}, get_connections(true)[0]);

    return (
        <React.Fragment>
            <style>{css_style}</style>
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

export default WidgetSquare;