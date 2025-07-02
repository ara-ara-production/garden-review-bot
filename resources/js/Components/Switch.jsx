import {FormFeedback, FormGroup, Input, Label} from "reactstrap";

export default ({
    label,
    placeHolder,
    value,
    onChange,
    error = null
                }) => {
    return (
            <FormGroup className="custom-control custom-checkbox">
                <Input
                    type="checkbox"
                    invalid={error !== null}
                    checked={value}
                    onChange={onChange}
                    role="switch"
                />
                <Label check>{label}</Label>
                <FormFeedback tooltip>
                    {error !== null ? error : null}
                </FormFeedback>
            </FormGroup>
    )
}
