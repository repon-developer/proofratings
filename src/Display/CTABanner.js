import { get_proofratings } from "../global";

const PreviewCTABanner = (props) => {
    const state = props;


    const get_styles = () => {
        const styles = []
        if (state?.star_color) {
            styles.push('--star_color:' + state.star_color);
        }

        if (state?.background_color) {
            styles.push('--backgroundColor:' + state.background_color);
        }

        if (state?.rating_text_color) {
            styles.push('--rating_text_color:' + state.rating_text_color);
        }

        if (state?.review_rating_background_color) {
            styles.push('--review_rating_background_color:' + state.review_rating_background_color);
        }

        if (state?.number_review_text_color) {
            styles.push('--reviewCountTextcolor:' + state.number_review_text_color);
        }

        return styles;
    }

    const css_style = `.proofratings-banner-badge {${get_styles().join(';')}}`;

    const cta_button_container = () => {
        const button1 = Object.assign({ show: true, text: 'Sign Up' }, state.button1);
        const button2 = Object.assign({ show: true, text: 'Sign Up' }, state.button2);

        return (
            <div className="button-container">
                {(button1.show === true && button1.text.length) && <div className="proofratings-button button1 has-border">{button1.text}</div>}
                {/* {(button2.show && button2.text.length) && <div className="proofratings-button button1 has-border">{button2.text}</div>} */}
            </div>
        )
    }

    return (
        <React.Fragment>
            <style>{css_style}</style>
            <div className={`proofratings-banner-badge ${state?.shadow ? 'has-shadow' : ''}`}>
                <div className="proofratings-logos">
                    <img src={`${get_proofratings().assets_url}/images/icon-google.png`} alt="google" />
                    <img src={`${get_proofratings().assets_url}/images/icon-wordpress.jpg`} alt="wordpress" />
                </div>
                <div className="rating-box">
                    <span className="proofratings-stars medium">
                        <i style={{ width: "100%" }} />
                    </span>
                    <span className="rating">5.0 / 5</span>
                </div>
                <div className="proofratings-review-count"># customer reviews</div>
                {cta_button_container()}
            </div>
        </React.Fragment>
    );
};

export default PreviewCTABanner;
