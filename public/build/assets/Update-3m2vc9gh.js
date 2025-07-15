import{x,J as h,r as j,j as e}from"./app-DfVTecIs.js";import{H as f}from"./Head-DX95bVhR.js";import{N as g}from"./NavBar-D8sRQfmC.js";import{C as b,R as w}from"./Row-D6qjDry_.js";import{C as t,B as C}from"./Alert-Cn5ir1qf.js";import{F as v,a as y}from"./Label-BQvcVMvc.js";import{R as l,a as N}from"./RowFormGroupSelect-D9-uDwpU.js";import{R}from"./RowFormGroupWithPrefix-JjnpBYJB.js";const D=({values:n,roles:i})=>{const{data:a,setData:o,put:p,processing:u,errors:s}=x({name:n.name??"",telegram_username:n.telegram_username??"",email:n.email??"",password:"",password_confirmation:"",role:n.role??"NullRole"}),{routes:m}=h().props,[_,d]=j.useState(!1),c=r=>{r.preventDefault(),p(`/${m.backendprefix}/${m.user}/${n.id}`)};return e.jsxs(e.Fragment,{children:[e.jsx(f,{title:"Обновление пользователя"}),e.jsx(g,{}),e.jsxs(b,{children:[e.jsx(w,{children:e.jsx(t,{children:e.jsx("h2",{children:"Обновление пользователя"})})}),e.jsxs(v,{className:"row",onSubmit:c,children:[e.jsxs(t,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(l,{label:"Имя *",inputType:"text",value:a.name,onChange:r=>o("name",r.target.value),error:s.name}),e.jsx(R,{label:"Телеграм никнейм",inputType:"text",value:a.telegram_username,onChange:r=>o("telegram_username",r.target.value),error:s.telegram_username,formText:"Необходимо заполнить, того, чтоб пользователь мог использовать бота"}),e.jsx(N,{label:"Роль",options:i,value:a.role,onChange:r=>o("role",r.target.value),error:s.role})]}),e.jsx("h6",{className:"pl-0 text-muted",children:"При заполнении пользователь сможет входить в админ панель:"}),e.jsxs(t,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(l,{label:"Почта",inputType:"email",value:a.email,onChange:r=>o("email",r.target.value),error:s.email}),e.jsx(l,{label:"Пароль",inputType:"password",value:a.password,onChange:r=>o("password",r.target.value),error:s.password}),e.jsx(l,{label:"Повтор пароля",inputType:"password",value:a.password_confirmation,onChange:r=>o("password_confirmation",r.target.value),error:s.password_confirmation})]}),e.jsx(t,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(y,{children:e.jsx(C,{className:"w-100",type:"submit",value:"Обновить",color:"primary",outline:!0,disabled:u,onClick:()=>d(!1),children:"Обновить"})})})]})]})]})};export{D as default};
