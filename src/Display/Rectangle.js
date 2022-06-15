import { get_settings } from '../widgets/Store';
import { get_proofratings } from "../global";
import ColorPicker from "./../Component/ColorPicker";
import Border from "./../Component/Border";
import Shadow from "./../Component/Shadow";
import ActiveSites from './../Component/ActiveSites'

const Rectangle = (props) => {
    const state = get_settings().widget_rectangle

    const border = Object.assign({ show: false, color: "", hover: "" }, state?.border)
    const shadow = Object.assign({ shadow: false, color: "", hover: "" }, state?.shadow)

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
            <div className="proofratings-review-widgets-grid proofratings-widgets-grid-rectangle">
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
        </React.Fragment>
    );
};

export default Rectangle;
