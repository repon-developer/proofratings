import store, { ACTIONS } from './Store';
import ColorPicker from "./ColorPicker";
import Border from "./Border";
import Shadow from "./Shadow";

const { useState, useEffect } = React;

const BadgeRectangle = () => {
    const [state, setState] = useState(Object.assign({customize: false}, store.getState().sites_square))

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setState(store.getState().sites_square))
        return () => unsubscribe();
    }, [])
    

    const handle_field = (data) => store.dispatch({ type: ACTIONS.SITES_SQUARE, payload: data})

    const border = Object.assign({ show: false, color: "", hover: "" }, state.border)
    const handleBorder = (name, value) => {
        border[name] = value;
        handle_field({border})
    }

    const shadow = Object.assign({ shadow: false, color: "", hover: "" }, state.shadow)
    const handleShadow = (name, value) => {
        shadow[name] = value;
        handle_field({shadow})
    }

    console.log(state)

    const styles = {}

    if ( state?.star_color ) {
        styles['--themeColor'] = state.star_color
    }

    if ( state?.textcolor ) {
        styles['--textColor'] = state.textcolor
    }

    return (
        <React.Fragment>
            <table className="form-table">
                <tbody>
                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>
                            Shortcode
                            <p className="description" style={{ fontWeight: "normal" }}>Use shortcode where you want to display review widgets</p>
                        </th>
                        <td>
                            <code className="shortocde-area">[proofratings_widgets style="square"]</code>
                        </td>
                    </tr>
                </tbody>
            </table>
            <label>
                <input
                    type="checkbox"
                    checked={state.customize}
                    className="checkbox-switch checkbox-yesno"
                    onChange={() => handle_field({customize: !state.customize})}
                />
                Customize (this will customize all badges)
            </label>

            {state.customize && <div className="gap-30" />}

            {state.customize && (
                <React.Fragment>
                    <div id="proofratings-badge-square" className="proofratings-review-widgets-grid proofratings-widgets-grid-square">
                        <div style={styles} className="proofratings-widget proofratings-widget-square proofratings-widget-customized">
                            <div className="review-site-logo" style={{WebkitMaskImage: 'url(http://proofratings.me/wp-content/plugins/proofratings/assets/images/yelp.svg)'}}>
                                <img src="http://proofratings.me/wp-content/plugins/proofratings/assets/images/yelp.svg" alt="Yelp" />
                            </div>
                            
                            <div className="proofratings-reviews" itemProp="reviewRating">
                                <span className="proofratings-score">0.0</span>
                                <span className="proofratings-stars"><i style={{width: '0%'}} /></span>
                            </div>
                            
                            <div className="review-count"> 0 reviews </div>
                            <p className="view-reviews">View Reviews</p>
                        </div>
                    </div>


                    <table className="form-table">
                        <tbody>
                            <tr>
                                <th scope="row">Star Color</th>
                                <td><ColorPicker color={state?.star_color} onUpdate={(star_color) => handle_field({star_color})} /></td>
                            </tr>

                            <tr>
                                <th scope="row">Site Icon Color</th>
                                <td><ColorPicker color={state?.icon_color} onUpdate={(icon_color) => handle_field({icon_color})} /></td>
                            </tr>

                            <tr>
                                <th scope="row">Text Color</th>
                                <td><ColorPicker color={state?.textcolor} onUpdate={(textcolor) => handle_field({textcolor})} /></td>
                            </tr>
                            <tr>
                                <th scope="row">Review count text color</th>
                                <td><ColorPicker color={state?.review_color_textcolor} onUpdate={(review_color_textcolor) => handle_field({review_color_textcolor})} /></td>
                            </tr>
                            <tr>
                                <th scope="row">Background Color</th>
                                <td><ColorPicker color={state?.background_color} onUpdate={(background_color) => handle_field({background_color})} /></td>
                            </tr>

                            <Border name="border" border={border} onUpdate={handleBorder} />
                            <Shadow shadow={shadow} onUpdate={handleShadow} />
                        </tbody>
                    </table>
                </React.Fragment>
            )}
        </React.Fragment>
    );
};

export default BadgeRectangle;
