import store, { ACTIONS } from "../Store";
import ColorPicker from "../ColorPicker";
import Shadow from "../Shadow";
import Widgets from "./Widgets"
import Link from "../Component/Link";

const { useState, useEffect } = React;

const OverallNarrow = (props) => {
    const [state, setState] = useState(store.getState().overall_narrow_embed);

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setState(store.getState().overall_narrow_embed));
        return () => unsubscribe();
    }, [])

    const handle_field = (data) => store.dispatch({ 
        type: ACTIONS.OVERALL_SAVE, 
        payload: {
            name: 'overall_narrow_embed', data
        }
    });

    const link = Object.assign({ enable: false, url: "", _blank: false }, state?.link)
    const handle_link = (name, value) => {
        link[name] = value;
        handle_field({link})
    }
    
    const shadow = Object.assign({ shadow: false, color: "", hover: "" }, state?.shadow)
    const handleShadow = (name, value) => {
        shadow[name] = value;
        handle_field({shadow})
    }

    return (
        <React.Fragment>
            <table className="form-table">
                <tbody>
                    <tr>
                        <th scope="row">Shortcode <p className="description" style={{ fontWeight: "normal" }}>Embed shortcode</p></th>
                        <td><code className="shortocde-area">[proofratings_overall_narrow id="{props?.id}"]</code></td>
                    </tr>

                    <Link {...link} onUpdate={handle_link} />

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
                                <th scope="row">Review Count Text Color</th>
                                <td><ColorPicker color={state?.review_text_color} onUpdate={(review_text_color) => handle_field({review_text_color})} /></td>
                            </tr>
                        </React.Fragment>
                    )}

                </tbody>
            </table>

        </React.Fragment>
    );
};

export default OverallNarrow;
