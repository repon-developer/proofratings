import store, { ACTIONS } from "./../Store";
import ColorPicker from "./../Component/ColorPicker";
import Widgets from "./Widgets";
import Link from '../Component/Link'
import Border from "./../Component/Border";
import Shadow from "./../Component/Shadow";

import ActiveSites from "../Component/ActiveSites";

const { useState, useEffect } = React;

const OverallRectangle_Embed = (props) => {
    const [state, setState] = useState(store.getState().overall_rectangle_embed);

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setState(store.getState().overall_rectangle_embed));
        return () => unsubscribe();
    }, [])

    const handle_field = (data) => store.dispatch({
        type: ACTIONS.OVERALL_SAVE,
        payload: {
            name: 'overall_rectangle_embed',
            data
        }
    });

    const link = Object.assign({ enable: false, url: "", _blank: false }, state?.link)
    const handle_link = (name, value) => {
        link[name] = value;
        handle_field({ link })
    }

    const border = Object.assign({ show: false, color: "", hover: "" }, state?.border)
    const handleBorder = (name, value) => {
        border[name] = value;
        handle_field({ border })
    }

    const shadow = Object.assign({ shadow: false, color: "", hover: "" }, state?.shadow)
    const handleShadow = (name, value) => {
        shadow[name] = value;
        handle_field({ shadow })
    }

    return (
        <React.Fragment>
            <div className="proofratings-copyarea">
                <h3>Shortcode</h3>
                <code className="shortocde-area">[proofratings_overall_rectangle id="{props?.id}"]</code>
                <p className="description">
                    Copy and paste this shortcode where you want to display the review badge. <br />
                    Note: Number of badges in a row is responsive and adjusts automatically to the space available
                </p>
            </div>

            <div className='gap-30' />

            <table className="form-table">
                <tbody>
                    <Link {...link} onUpdate={handle_link} />


                    <Widgets {...state} shadow={shadow} border={border} />

                    <tr>
                        <th scope="row">Star Color</th>
                        <td><ColorPicker color={state?.star_color} onUpdate={(star_color) => handle_field({ star_color })} /></td>
                    </tr>

                    <tr>
                        <th scope="row">Rating Color</th>
                        <td><ColorPicker color={state?.rating_color} onUpdate={(rating_color) => handle_field({ rating_color })} /></td>
                    </tr>

                    <Border name="border" border={border} onUpdate={handleBorder} />

                    <Shadow shadow={shadow} onUpdate={handleShadow} />

                    <tr>
                        <th scope="row">Background Color</th>
                        <td><ColorPicker color={state?.background_color} onUpdate={(background_color) => handle_field({ background_color })} /></td>
                    </tr>
                    <tr>
                        <th scope="row">Review Text Color</th>
                        <td><ColorPicker color={state?.review_text_color} onUpdate={(review_text_color) => handle_field({ review_text_color })} /></td>
                    </tr>
                    <tr>
                        <th scope="row">Review Background Color</th>
                        <td><ColorPicker color={state?.review_background} onUpdate={(review_background) => handle_field({ review_background })} /></td>
                    </tr>

                </tbody>
            </table>
        </React.Fragment>
    );
};

export default OverallRectangle_Embed;
