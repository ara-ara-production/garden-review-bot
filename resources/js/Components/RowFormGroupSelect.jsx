import {Col, FormFeedback, FormGroup, FormText, Input, Label} from "reactstrap";

export default ({
                    label,
                    placeHolder,
                    value,
                    onChange,
                    options = [],
                    error = null,
                    formText = null,
                }) => {

    return (
        <FormGroup row className="mb-4">
            <Label sm={3}>{label}</Label>
            <Col sm={9}>
                <Input
                    placeholder={placeHolder}
                    type="select"
                    invalid={error !== null}
                    value={value}
                    onChange={onChange}
                >
                    {options.map((option, i) => <option key={`option-${i}`} value={option.name}>{option.value}</option>)}
                </Input>
                {formText ? <FormText>{formText}</FormText> : null}
                {error ? <FormFeedback>{error}</FormFeedback> : null}
            </Col>

        </FormGroup>
    )
}
