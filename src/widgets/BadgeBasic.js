import store, { ACTIONS, get_settings } from '../widgets/Store';
import { get_proofrating } from "../global";
import ColorPicker from "./../Component/ColorPicker";
import ActiveSites from './../Component/ActiveSites';

const { useState, useEffect } = React;

const BadgeBasic = (props) => {
    const [state, setState] = useState(get_settings().widget_basic)

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setState(get_settings().widget_basic))
        return () => unsubscribe();
    }, [])

    const handle_field = (data) => store.dispatch({ type: ACTIONS.WIDGET_BASIC, payload: data })

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

            <div className="proofratings-copyarea">
                <h3>Shortcode</h3>
                <code className="shortocde-area">[proofratings_widgets id="{props?.id}" style="basic"]</code>
                <p className="description">
                    Copy and paste this shortcode where you want to display the review badge. <br />
                    Note: Number of badges in a row is responsive and adjusts automatically to the space available
                </p>
            </div>

            <ActiveSites onUpdate={(widget_connections) => handle_field({ widget_connections })} widget_connections={state?.widget_connections} />

            <h3>Color Selection</h3>
            <div className="proofratings-review-widgets-grid proofratings-widget-grid-basic">
                <div className={widget_classes}>
                    <div className="review-site-logo">
                        <img src={`${get_proofrating().assets_url}images/google.svg`} alt="Google" />
                    </div>

                    <div className="proofratings-stars"><i style={{ width: '100%' }} /></div>

                    <div className="review-count"># ratings</div>
                    <a className="view-reviews" href="#">View all reviews</a>
                </div>
            </div>


            <table className="form-table">
                <tbody>
                    <tr>
                        <th scope="row">Star Color</th>
                        <td><ColorPicker color={state?.star_color} onUpdate={(star_color) => handle_field({ star_color })} /></td>
                    </tr>

                    <tr>
                        <th scope="row">Review Count Color</th>
                        <td><ColorPicker color={state?.review_count_textcolor} onUpdate={(review_count_textcolor) => handle_field({ review_count_textcolor })} /></td>
                    </tr>

                    <tr>
                        <th scope="row">View Reviews text color</th>
                        <td><ColorPicker color={state?.view_reviews_text_color} onUpdate={(view_reviews_text_color) => handle_field({ view_reviews_text_color })} /></td>
                    </tr>
                </tbody>
            </table>

        </React.Fragment>
    );
};

export default BadgeBasic;
