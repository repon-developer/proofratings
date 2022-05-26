import ColorPicker from "./ColorPicker";

const PopupWidget = (props) => {
    const state = props.settings;

    const get_styles = () => {
        const styles = []
        if (state?.star_color) {
            styles.push('--themeColor:' + state.star_color);
        }

        if (state?.logo_color) {
            styles.push('--logoColor:' + state.logo_color);
        }

        if (state?.review_text_color) {
            styles.push('--reviewCountTextColor:' + state.review_text_color);
        }

        if (state?.review_text_background) {
            styles.push('--review_text_background:' + state.review_text_background);
        }

        if (state?.rating_color) {
            styles.push('--rating_color:' + state.rating_color);
        }

        if (state?.view_review_color) {
            styles.push('--view_review_color:' + state.view_review_color);
        }

        return styles;
    }

    const css_style = `.proofratings-popup-widgets-box .proofratings-widget{${get_styles().join(';')}}`;

    let widget_class = 'proofratings-widget proofratings-widget-customized';
    if (state?.logo_color) {
        widget_class += ' proofratings-widget-logo-color';
    }

    return (
        <React.Fragment>
            <style>{css_style}</style>
            <h2 className="section-title-large" style={{ marginBottom: 15 }}>Badges on Pop-up</h2>

            <div className="proofratings-badges-popup">
                <div className="proofratings-popup-widgets-box" data-column={2}>
                    <div className={widget_class}>
                        <div className="review-site-logo">
                            <img src={`${proofratings.assets_url}/images/google.svg`} alt="Google" />
                        </div>
                        <div className="proofratings-reviews" itemProp="reviewRating">
                            <span className="proofratings-score">5.0</span>
                            <span className="proofratings-stars">
                                <i style={{ width: "100%" }} />
                            </span>
                        </div>
                        <div className="review-count"> 9 reviews </div>
                        <p className="view-reviews">View Reviews</p>
                    </div>

                    <div className={widget_class}>
                        <div className="review-site-logo">
                            <img src={`${proofratings.assets_url}/images/wordpress.svg`} alt="Wordpress" />
                        </div>
                        <div className="proofratings-reviews" itemProp="reviewRating">
                            <span className="proofratings-score">5.0</span>
                            <span className="proofratings-stars">
                                <i style={{ width: "100%" }} />
                            </span>
                        </div>
                        <div className="review-count"> 25 reviews </div>
                        <p className="view-reviews">View Reviews</p>
                    </div>
                </div>
            </div>

            <table className="form-table">
                <tbody>
                    <tr>
                        <th scope="row">Star Color</th>
                        <td><ColorPicker color={state?.star_color} onUpdate={(star_color) => props.onUpdate('star_color', star_color)} /></td>
                    </tr>

                    <tr>
                        <th scope="row">Review Text Color</th>
                        <td><ColorPicker color={state?.review_text_color} onUpdate={(review_text_color) => props.onUpdate('review_text_color', review_text_color)} /></td>
                    </tr>

                    <tr>
                        <th scope="row">Review Background Color</th>
                        <td><ColorPicker color={state?.review_text_background} onUpdate={(review_text_background) => props.onUpdate('review_text_background', review_text_background)} /></td>
                    </tr>

                    <tr>
                        <th scope="row">Rating Color</th>
                        <td><ColorPicker color={state?.rating_color} onUpdate={(rating_color) => props.onUpdate('rating_color', rating_color)} /></td>
                    </tr>

                    <tr>
                        <th scope="row">View Review Color</th>
                        <td><ColorPicker color={state?.view_review_color} onUpdate={(view_review_color) => props.onUpdate('view_review_color', view_review_color)} /></td>
                    </tr>
                </tbody>
            </table>
        </React.Fragment>
    );
};

export default PopupWidget;
