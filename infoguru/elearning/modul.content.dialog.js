validateInput = function ()
{
    var valx = new ValidatorX();
    return valx.EmptyText("judul", "Judul") &&
           valx.TextLength("judul", "Judul", 5, 255) &&
           valx.EmptyText("urutan", "Urutan") &&
           valx.IsInteger("urutan", "Urutan");
};