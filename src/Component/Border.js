import ColorPicker from "./ColorPicker";

const Border = (props) => {
    const {show, color, hover} = props.border

    const update_border = (name, value) => props.onUpdate(name, value)

    return (
        <React.Fragment>
            <tr>
                <th scope="row">Border</th>
                <td>
                    <input
                        type="checkbox"
                        checked={show}
                        onChange={() => update_border('show', !show)}
                        className="checkbox-switch"
                    />
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
