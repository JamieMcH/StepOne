#!ipxe

set arch x86_64

:root-menu
imgfree
menu
item exit                 Exit iPXE and continue normal boot
item --gap --
item --gap -- --------------- iPXE Install Menu ---------------
item rocky                Rocky Linux Server
item fedora               Fedora KDE Workstation
item --gap -- --------------- Advanced Options ---------------
item shell                Enter iPXE Shell
item reload               Reload iPXE
choose --default rocky target && goto ${target}

:shell
shell

:reload
chain --replace tftp://rusty.jagapps.tech/boot.php

:rocky
set web-domain dl.rockylinux.org
set url https://${web-domain}/pub/rocky
menu
item prev                 Previous Screen
item --gap --
item --gap -- --------------- Rocky Linux Release -------------
item 10                   10
item 9                    9
choose --default 10 release
iseq ${release} prev && goto root-menu ||
set repo ${url}/${release}/BaseOS/${arch}/kickstart
set kernel ${repo}/images/pxeboot/vmlinuz
set initrd ${repo}/images/pxeboot/initrd.img
goto rhel-boot

:fedora
set url https://dl.fedoraproject.org/pub/fedora/linux/releases
menu
item prev                 Previous Screen
item --gap --
item --gap -- --------------- Fedora Linux Release ------------
item 43                   43
item 42                   42
choose --default 43 release
iseq ${release} prev && goto root-menu ||
set repo ${url}/${release}/Everything/${arch}/os
set kernel ${repo}/images/pxeboot/vmlinuz
set initrd ${repo}/images/pxeboot/initrd.img
goto rhel-boot

:rhel-boot
echo Downloading Kernel...
kernel ${kernel} ip=dhcp inst.repo=${repo} initrd=initrd.img
echo Downloading Initial RAM Disk...
initrd ${initrd}
imgstat
sleep 5
boot --autofree --replace
