import Head from "@/Components/Head.jsx";
import React, {useState} from "react";
import NavBar from "@/Components/NavBar.jsx";
import {Button, Col, Container, Form, Row, FormGroup, Alert} from "reactstrap";
import {useForm, usePage} from "@inertiajs/react";
import RowFormGroup from "@/Components/RowFormGroup.jsx";
import RowFormGroupWithPrefix from "@/Components/RowFormGroupWithPrefix.jsx";
import SubmitButton from "@/Components/SubmitButton.jsx";
import RowFormGroupSelect from "@/Components/RowFormGroupSelect.jsx";

export default ({values, roles}) => {

    const {
        data,
        setData,
        put,
        processing,
        errors,

    } = useForm({
        name: values.name ?? '',
        telegram_username: values.telegram_username ?? '',
        email: values.email ?? '',
        password: '',
        password_confirmation: '',
        role: values.role ?? 'NullRole'
    })

    const {routes} = usePage().props;
    const [createAnotherOne, setCreateAnotherOne] = useState(false);

    const submit = (e) => {
        e.preventDefault();
        put(`/${routes.backendprefix}/${routes.user}/${values.id}`);
    }
    return (<>
        <Head title="Обновление пользователя"/>
        <NavBar/>
        <Container>
            <Row><Col><h2>Обновление пользователя</h2></Col></Row>
            <Form className="row" onSubmit={submit}>
                <Col
                    className="
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        "
                >
                    <RowFormGroup
                        label="Имя *"
                        inputType="text"
                        value={data.name}
                        onChange={e => setData('name', e.target.value)}
                        error={errors.name}
                    />
                    <RowFormGroupWithPrefix
                        label="Телеграм никнейм"
                        inputType="text"
                        value={data.telegram_username}
                        onChange={e => setData('telegram_username', e.target.value)}
                        error={errors.telegram_username}
                        formText="Необходимо заполнить, того, чтоб пользователь мог использовать бота"
                    />
                    <RowFormGroupSelect
                        label="Роль"
                        options={roles}
                        value={data.role}
                        onChange={e => setData('role', e.target.value)}
                        error={errors.role}
                    />
                </Col>

                <h6 className="pl-0 text-muted">При заполнении пользователь сможет входить в админ панель:</h6>
                <Col
                    className="
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        "
                >
                    <RowFormGroup
                        label="Почта"
                        inputType="email"
                        value={data.email}
                        onChange={e => setData('email', e.target.value)}
                        error={errors.email}
                    />
                    <RowFormGroup
                        label="Пароль"
                        inputType="password"
                        value={data.password}
                        onChange={e => setData('password', e.target.value)}
                        error={errors.password}
                    />
                    <RowFormGroup
                        label="Повтор пароля"
                        inputType="password"
                        value={data.password_confirmation}
                        onChange={e => setData('password_confirmation', e.target.value)}
                        error={errors.password_confirmation}
                    />
                </Col>
                <Col
                    className="
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        "
                >
                    <FormGroup>
                        <Button
                            className="w-100"
                            type="submit"
                            value="Обновить"
                            color="primary"
                            outline
                            disabled={processing}
                            onClick={() => setCreateAnotherOne(false)}
                        >Обновить</Button>
                    </FormGroup>
                </Col>
            </Form>
        </Container>
    </>);
}
