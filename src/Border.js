import ColorPicker from "./ColorPicker";

const Border = (props) => {
    const border = Object.assign({ show: false, color: "", hover: "" }, props.border);

    const update_border = (data) => {
        if ( typeof props.onUpdate !== 'function' ) {
            return;
        }

        props.onUpdate({...border, ...data});
    }

    return (
        <React.Fragment>
            <tr>
                <th scope="row">Border</th>
                <td>
                    <input
                        type="checkbox"
                        checked={border.show}
                        onChange={() => update_border({show: !border.show})}
                        className="checkbox-switch"
                    />
                </td>
            </tr>

            {border.show && (
                <>
                    <tr>
                        <th scope="row">Border Color</th>
                        <td>
                            <ColorPicker
                                name="color"
                                color={border.color}
                                onUpdate={(color) => update_border({color})}
                            />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Border Hover Color</th>
                        <td>
                            <ColorPicker
                                name="hover"
                                color={border.hover}
                                onUpdate={(hover) => update_border({hover})}
                            />
                        </td>
                    </tr>
                </>
            )}
        </React.Fragment>
    );
};

export default Border;
