const { useEffect, useRef } = React;

const ColorPicker = (props) => {
    const colorInput = useRef(null);
    const color = props?.color;
    const defaultColor = props?.defaultValue;

    useEffect(() => {
        jQuery(colorInput.current).wpColorPicker({
            change: function(event, ui) {
                if ( typeof props.onUpdate === 'function' && props?.name ) {
                    props.onUpdate(ui.color.toString())
                }
            },
    
            clear: function (event) {
                if ( typeof props.onUpdate === 'function' && props?.name ) {
                    props.onUpdate('')
                }
            }
        })
    }, [])

    return <input class="proofratings-color-field" type="text" ref={colorInput} value={color} data-default-color={defaultColor} />
}

export default ColorPicker;