<IfModule mod_python.c>
    <Files ~ (\.py$)>
        SetHandler python-program
        PythonHandler mod_python.cgihandler
    </Files>
</IfModule>
