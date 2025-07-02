import Head from "@/Components/Head.jsx";
import {Alert, Col, Container, Form, Row} from "reactstrap";
import {useForm, usePage} from "@inertiajs/react";
import FormGroup from "@/Components/FormGroup.jsx";
import Switch from "@/Components/Switch.jsx";
import SubmitButton from "@/Components/SubmitButton.jsx";
import NavBar from "@/Components/NavBar.jsx";

export default () => {
    const {
        data,
        setData,
        post,
        processing,
        errors,

    } = useForm({
        email: '',
        password: '',
        remember: false,
    })

    const submit = (e) => {
        e.preventDefault()
        post('/login')
    }

    return (
        <>
            <Head
                title="Вход"
            />
            <NavBar/>
            <Container fluid className="h-100 d-flex align-items-center justify-content-center">
                <Row>
                    <Col
                        className="
                        border
                        border-primary
                        rounded
                        col-12
                        col-sm-auto
                    ">
                        <h2 size="xxl">Вход</h2>
                        {errors.auth !== undefined ? <Alert color="danger">{errors.auth}</Alert> : null}

                        <Form onSubmit={submit}>
                            <FormGroup
                                label='Почта'
                                inputType='email'
                                value={data.email}
                                onChange={e => setData('email', e.target.value)}
                                error={errors.email}
                            />
                            <FormGroup
                                label='Пароль'
                                inputType='password'
                                value={data.password}
                                onChange={e => setData('password', e.target.value)}
                                error={errors.password}
                            />
                            <Switch
                                label="Запомнить меня?"
                                value={data.remember}
                                onChange={e => setData('remember', e.target.checked)}
                                error={errors.remember}
                            />
                            <SubmitButton
                                buttonText="Войти"
                                type="submit"
                                processing={processing}
                            />
                        </Form>
                    </Col>
                </Row>
            </Container>
        </>
    )
}
