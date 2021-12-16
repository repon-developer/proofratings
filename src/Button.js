import ColorPicker from "./Component/ColorPicker";

const Button = (props) => {
    return (
        <React.Fragment>

            <tr>
                <th scope="row">Button Text</th>
                <td>
                    <input
                        type="text"
                        defaultValue={props?.text}
                        onChange={(e) => props.onUpdate('text', e.target.value)}
                    />
                </td>
            </tr>
            <tr>
                <th scope="row">Button URL</th>
                <td>
                    <input
                        type="url"
                        defaultValue={props?.url}
                        onChange={(e) => props.onUpdate('url', e.target.value)}
                    />
                    <label style={{ marginLeft: 10 }}>
                        <input
                            type="checkbox"
                            defaultChecked={props?.blank}
                            className="checkbox-switch checkbox-onoff"
                            onChange={(e) => props.onUpdate('blank', !props?.blank)}
                        /> Open in new tab
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row">Button Text Color</th>
                <td><ColorPicker color={props?.textcolor} onUpdate={(textcolor) => props.onUpdate('textcolor', textcolor)} /></td>
            </tr>
            <tr>
                <th scope="row">Button Background Color</th>
                <td><ColorPicker color={props?.background_color} onUpdate={(background_color) => props.onUpdate('background_color', background_color)} /></td>
            </tr>
            <tr>
                <th scope="row">Button Shape</th>
                <td>
                    <input
                        type="checkbox"
                        defaultChecked={props?.shape}
                        className="checkbox-switch checkbox-shape"
                        onChange={() => props.onUpdate('shape', !props?.shape)}
                    />
                </td>
            </tr>
            <tr>
                <th scope="row">Button Border</th>
                <td>
                    <input
                        type="checkbox"
                        defaultChecked={props?.border}
                        className="checkbox-switch"
                        onChange={() => props.onUpdate('border', !props?.border)}
                    />
                </td>
            </tr>

            {props?.border &&
                <tr>
                    <th scope="row">Button Border Color</th>
                    <td><ColorPicker color={props?.border_color} onUpdate={(color) => props.onUpdate('border_color', color)} /></td>
                </tr>
            }

            <tr>
                <th scope="row">Button Hover Text Color</th>
                <td><ColorPicker color={props?.hover_textcolor} onUpdate={(color) => props.onUpdate('hover_textcolor', color)} /></td>
            </tr>

            <tr>
                <th scope="row">Button Hover Background Color</th>
                <td><ColorPicker color={props?.hover_background_color} onUpdate={(color) => props.onUpdate('hover_background_color', color)} /></td>
            </tr>

            {props?.border &&
                <tr>
                    <th scope="row">Button Hover Border Color</th>
                    <td><ColorPicker color={props?.hover_border_color} onUpdate={(color) => props.onUpdate('hover_border_color', color)} /></td>
                </tr>
            }
        </React.Fragment>
    );
};

export default Button;
