import {
    Alert,
    Button, Col,
    Collapse,
    Container,
    Nav,
    Navbar,
    NavbarText,
    NavbarToggler,
    NavItem,
    NavLink,
    Progress
} from "reactstrap";
import React, {useEffect, useRef, useState} from "react";
import {router, usePage} from "@inertiajs/react";

export default () => {
    const [toggler, setToggler] = useState(false);
    const {auth, routes, flash} = usePage().props;

    const [visible, setVisible] = useState(false);
    const [progress, setProgress] = useState(100);

    useEffect(() => {
        if (flash.message != null) {
            setVisible(true);
            setProgress(100);

            let start = Date.now();
            const interval = setInterval(() => {
                const elapsed = Date.now() - start;
                setProgress(Math.max(elapsed, 0));

                if (elapsed > 3500) {
                    clearInterval(interval);
                    setVisible(false);
                }
            }, 100);

            return () => clearInterval(interval);
        }
    }, [flash.message]);

    return (<>
        <Navbar color="primary" dark expand="md">
            <NavbarToggler onClick={() => setToggler(!toggler)}/>
            <Collapse isOpen={toggler} navbar>
                <Nav className="me-auto" navbar>
                    <NavItem>
                        <NavLink
                            href=""
                            onClick={e => {
                                e.preventDefault();
                                router.visit(`/${routes.backendprefix}/${routes.user}`)
                            }}
                        >
                            Пользователи
                        </NavLink>
                    </NavItem>
                    <NavItem>
                        <NavLink
                            href=""
                            onClick={e => {
                                e.preventDefault();
                                router.visit(`/${routes.backendprefix}/${routes.brunch}`)
                            }}
                        >Филиалы</NavLink>
                    </NavItem>
                    <NavItem>
                        <NavLink
                            href=""
                            onClick={e => {
                                e.preventDefault();
                                router.visit(`/${routes.review_table_prefix}/${routes.review}`)
                            }}
                        >Отзывы</NavLink>
                    </NavItem>
                </Nav>
                <NavbarText>

                    {auth.user ?
                        <Button onClick={e => {
                            e.preventDefault();
                            router.post('/logout')
                        }}>
                            Выйти
                        </Button>
                        : null}
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
                        className="rounded-0" color={flash.message.status} value={progress} max={3000}/></Col> : null}
        </Container>
    </>)
}
