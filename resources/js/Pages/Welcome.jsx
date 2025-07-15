import {
    Alert,
    Button,
    Col,
    Collapse,
    Container,
    Nav,
    Navbar, NavbarBrand,
    NavbarText,
    NavbarToggler,
    NavItem,
    NavLink, Progress,
    Row
} from "reactstrap";
import {Head, router} from '@inertiajs/react'
import React, {useState} from "react";


export default () => {
    const [toggler, setToggler] = useState(false);
    const [visible, setVisible] = useState(false);
    return (
        <>
            <Navbar color="primary" dark expand="md">
                <NavbarBrand href="/">
                    <img
                        alt="logo"
                        src='/logo.png'
                        style={{
                            height: 40,
                        }}
                    />

                    Garden
                </NavbarBrand>
                <NavbarToggler onClick={() => setToggler(!toggler)}/>
                <Collapse isOpen={toggler} navbar>
                    <Nav className="me-auto" navbar></Nav>
                    <NavbarText>
                        <Button onClick={e => {
                            e.preventDefault();
                            router.get('/login')
                        }}>
                            Войти
                        </Button>

                    </NavbarText>
                </Collapse>
            </Navbar>
            <Container fluid className="position-relative">
                {visible
                    ? <Col
                        style={{zIndex: 5}}
                        className="rounded col-6 offset-6 position-absolute"
                    >
                        <Alert
                            className="mb-0 rounded-0"
                            isOpen={visible}
                            fade={true}
                            color={flash.message.status}
                        >
                            {flash.message.text}
                        </Alert>
                        <Progress
                            className="rounded-0" color={flash.message.status} value={progress}
                            max={3000}/></Col> : null}
            </Container>
            <Container fluid className="h-100 d-flex align-items-center justify-content-center">
                <Row>
                    <h2>Публичная веб страница телеграм бота!</h2>
                </Row>
            </Container>
        </>
    )
}
