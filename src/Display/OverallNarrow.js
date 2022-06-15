import { get_proofratings } from "../global";

const DisplayOverallNarrow = (props) => {

    const get_styles = () => {
        const styles = []
        if (props?.star_color) {
            styles.push('--star_color:' + props.star_color);
        }

        if (props?.rating_color) {
            styles.push('--rating_color:' + props.rating_color);
        }

        if (props?.background_color) {
            styles.push('--background_color:' + props.background_color);
        }

        if (props?.review_text_color) {
            styles.push('--review_text_color:' + props.review_text_color);
        }

        if (props?.border?.show === false) {
            styles.push('--border_color: transparent');
            styles.push('--border_hover: transparent');
        }

        if (props?.border?.show !== false && props?.border?.color) {
            styles.push('--border_color:' + props.border.color);
        }

        if (props?.border?.show !== false && props?.border?.hover) {
            styles.push('--border_hover:' + props.border.hover);
        }

        if (props?.shadow?.shadow === false) {
            styles.push('--shadow_color: transparent');
            styles.push('--shadow_hover: transparent');
        }

        if (props?.shadow?.shadow !== false && props?.shadow?.color) {
            styles.push('--shadow_color:' + props.shadow.color);
        }

        if (props?.shadow?.shadow !== false && props?.shadow?.hover) {
            styles.push('--shadow_hover:' + props.shadow.hover);
        }

        return styles;
    }

    const css_style = `.proofratings-badge.proofratings-badge-narrow {${get_styles().join(';')}}`;

    return (
        <React.Fragment>
            <style>{css_style}</style>
            <div className="proofratings-badge proofratings-badge-narrow">
                <div className="proofratings-logos">
                    <img src={`${get_proofratings().assets_url}/images/icon-google.png`} alt="google" />
                    <img src={`${get_proofratings().assets_url}/images/icon-wordpress.jpg`} alt="wordpress" />
                </div>
                <div className="proofratings-reviews">
                    <span className="proofratings-score">5.0</span>
                    <span className="proofratings-stars">
                        <i style={{ width: "100%" }} />
                    </span>
                </div>
                <div className="proofratings-review-count"># reviews</div>
            </div>
        </React.Fragment>
    )
}

export default DisplayOverallNarrow;