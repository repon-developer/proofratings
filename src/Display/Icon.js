import { get_settings } from '../widgets/Store';
import { get_proofratings } from "../global";

const Icon = () => {
    const state = get_settings().widget_icon

    const get_styles = () => {
        const styles = []
        if (state?.star_color) {
            styles.push('--themeColor:' + state.star_color);
        }

        if (state?.icon_color) {
            styles.push('--logoColor:' + state.icon_color);
        }

        if (state?.textcolor) {
            styles.push('--textcolor:' + state.textcolor);
        }


        return styles;
    }

    let css_style = `.proofratings-widget.proofratings-widget-icon {${get_styles().join(';')}}`;

    let widget_classes = 'proofratings-widget proofratings-widget-icon proofratings-widget-customized';
    if (state?.icon_color) {
        widget_classes += ' proofratings-widget-logo-color';
    }

    return (
        <React.Fragment>
            <style>{css_style}</style>

            <div className="proofratings-review-widgets-grid proofratings-widget-grid-icon" style={{ padding: '10px 15px', backgroundColor: '#fff' }}>
                <div className={widget_classes}>
                    <div className="review-site-logo" style={{ WebkitMaskImage: `url(${get_proofratings().assets_url}images/icon3-yelp.svg)` }}>
                        <img src={`${get_proofratings().assets_url}images/icon3-yelp.svg`} alt="Yelp" />
                    </div>

                    <div className="review-info-container">
                        <span className="proofratings-stars"><i style={{ width: '100%' }}></i></span>
                        <div className="review-info">
                            <span className="proofratings-rating">5.0 Rating</span>
                            <span className="separator-circle">●</span>
                            <span className="proofratings-review-number"># Reviews</span>
                        </div>
                    </div>
                </div>
            </div>
        </React.Fragment>
    );
};

export default Icon;
