import {FormFeedback, FormGroup, Input, Label} from "reactstrap";

export default ({
    label,
    inputType,
    placeHolder,
    value,
    onChange,
    error = null
                }) => {
    return (
            <FormGroup className="position-relative">
                <Label>{label}</Label>
                <Input
                    placeholder={placeHolder}
                    type={inputType}
                    invalid={error !== null}
                    value={value}
                    onChange={onChange}
                />
                <FormFeedback tooltip>
                    {error !== null ? error: null}
                </FormFeedback>
            </FormGroup>
    )
}
