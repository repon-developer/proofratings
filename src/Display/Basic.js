import { get_settings } from '../widgets/Store';
import { get_proofratings } from "../global";


const Basic = (props) => {
    const state = get_settings().widget_basic

    const get_styles = () => {
        const styles = []
        if (state?.star_color) {
            styles.push('--themeColor:' + state.star_color);
        }

        if (state?.logo_color) {
            styles.push('--logoColor:' + state.logo_color);
        }

        if (state?.review_count_textcolor) {
            styles.push('--review_count_textcolor:' + state.review_count_textcolor);
        }

        if (state?.view_reviews_text_color) {
            styles.push('--view_review_textcolor:' + state.view_reviews_text_color);
        }

        return styles;
    }

    let css_style = `.proofratings-widget.proofratings-widget-basic {${get_styles().join(';')}}`;

    let widget_classes = 'proofratings-widget proofratings-widget-basic proofratings-widget-customized';
    if (state?.logo_color) {
        widget_classes += ' proofratings-widget-logo-color';
    }

    if (state?.alignment) {
        widget_classes += ' proofratings-widgets-align-' + state.alignment;
    }

    return (
        <React.Fragment>
            <style>{css_style}</style>


            <div className={widget_classes}>
                <div className="review-site-logo">
                    <img src={`${get_proofratings().assets_url}images/google.svg`} alt="Google" />
                </div>

                <div className="proofratings-stars"><i style={{ width: '100%' }} /></div>

                <div className="review-count"># ratings</div>
                <a className="view-reviews" href="#">View all reviews</a>
            </div>

        </React.Fragment>
    );
};

export default Basic;
