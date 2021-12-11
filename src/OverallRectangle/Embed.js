import store, { ACTIONS } from "./../Store";
import ColorPicker from "./../ColorPicker";
import Shadow from "./../Shadow";
import Widgets from "./Widgets";

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

    const shadow = Object.assign({ shadow: false, color: "", hover: "" }, state?.shadow)
    const handleShadow = (name, value) => {
        shadow[name] = value;
        handle_field({shadow})
    }

    return (
        <table className="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        Shortcode
                        <p className="description" style={{ fontWeight: "normal" }}>Embed shortcode</p>
                    </th>
                    <td>
                        <code className="shortocde-area">
                            [proofratings_overall_ratings id="{props?.id}" type="rectangle"]
                        </code>
                    </td>
                </tr>

                <tr>
                    <td style={{ paddingLeft: 0 }} colSpan={2}>
                        <label>
                            <input
                                type="checkbox"
                                className="checkbox-switch"
                                defaultChecked={state?.customize}
                                onChange={() => handle_field({customize: !state?.customize})}
                            /> Customize
                        </label>
                    </td>
                </tr>

                {state?.customize && (
                    <React.Fragment>
                        <Widgets {...state} shadow={shadow} />

                        <tr>
                            <th scope="row">Star Color</th>
                            <td><ColorPicker color={state?.star_color} onUpdate={(star_color) => handle_field({star_color})} /></td>
                        </tr>

                        <tr>
                            <th scope="row">Rating Color</th>
                            <td><ColorPicker color={state?.rating_color} onUpdate={(rating_color) => handle_field({rating_color})} /></td>
                        </tr>

                        <Shadow shadow={shadow} onUpdate={handleShadow} />
                    
                        <tr>
                            <th scope="row">Background Color</th>
                            <td><ColorPicker color={state?.background_color} onUpdate={(background_color) => handle_field({background_color})} /></td>
                        </tr>
                        <tr>
                            <th scope="row">Review Text Color</th>
                            <td><ColorPicker color={state?.review_text_color} onUpdate={(review_text_color) => handle_field({review_text_color})} /></td>
                        </tr>
                        <tr>
                            <th scope="row">Review Background Color</th>
                            <td><ColorPicker color={state?.review_background} onUpdate={(review_background) => handle_field({review_background})} /></td>
                        </tr>
                    </React.Fragment>
                )}

            </tbody>
        </table>
    );
};

export default OverallRectangle_Embed;
