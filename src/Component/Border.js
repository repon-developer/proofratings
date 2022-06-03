import ColorPicker from "./ColorPicker";

const Border = (props) => {
    const {show, color, hover} = props.border

    const update_border = (name, value) => props.onUpdate(name, value)

    return (
        <React.Fragment>
            <tr>
                <th scope="row">Border</th>
                <td>
                    <label className="label-switch-checkbox">
                        <input className="checkbox-switch" type="checkbox" onChange={() => update_border('show', !show)} checked={show} />
                        <span>No Border</span>
                        <span>Add Border</span>
                    </label>
                </td>
            </tr>

            {show && (
                <>
                    <tr>
                        <th scope="row">Border Color</th>
                        <td>
                            <ColorPicker
                                name="color"
                                color={color}
                                onUpdate={(color) => update_border('color', color)}
                            />

                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Border Hover Color</th>
                        <td>
                            <ColorPicker
                                name="hover"
                                color={hover}
                                onUpdate={(hover) => update_border('hover', hover)}
                            />
                        </td>
                    </tr>
                </>
            )}
        </React.Fragment>
    );
};

export default Border;
