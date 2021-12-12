const Widgets = (props) => {

    const get_styles = () => {
        const styles = []
        if ( props?.star_color ) {
            styles.push('--star_color:' + props.star_color);
        }
    
        if ( props?.rating_color ) {
            styles.push('--rating_color:' + props.rating_color);
        }

        if ( props?.background_color ) {
            styles.push('--background_color:' + props.background_color);
        }

        if ( props?.review_text_color ) {
            styles.push('--review_text_color:' + props.review_text_color);
        }

        if ( props?.review_background ) {
            styles.push('--review_background:' + props.review_background);
        }

        if ( props?.shadow?.shadow === false ) {
            styles.push('--shadow_color: transparent');
            styles.push('--shadow_hover: transparent');
        }

        if ( props?.shadow?.shadow !== false && props?.shadow?.color ) {
            styles.push('--shadow_color:' + props.shadow.color);
        }

        if ( props?.shadow?.shadow !== false && props?.shadow?.hover ) {
            styles.push('--shadow_hover:' + props.shadow.hover);
        }
    
        return styles;
    }

    css_style = `.proofratings-badge.proofratings-badge-rectangle {${get_styles().join(';')}}`;

    return(
        <React.Fragment>
            <style>{css_style}</style>
            <tr>
                <td colSpan={2} style={{paddingLeft: 0}}>
                    <div className="proofratings-badge proofratings-badge-rectangle">
                        <div className="proofratings-inner">
                            <div className="proofratings-logos">
                                <img src={`${proofratings.assets_url}/images/icon-google.png`} alt="google"/>
                                <img src={`${proofratings.assets_url}/images/icon-trustpilot.png`} alt="trustpilot"/>
                                <img src={`${proofratings.assets_url}/images/icon-wordpress.jpg`} alt="wordpress"/>
                            </div>
                            <div className="proofratings-reviews">
                                <span className="proofratings-score">4.8</span>
                                <span className="proofratings-stars">
                                    <i style={{ width: "96%" }} />
                                </span>
                            </div>
                        </div>
                        <div className="proofratings-review-count">44 reviews</div>
                    </div>
                </td>
            </tr>
        </React.Fragment>
    )
}

export default Widgets;