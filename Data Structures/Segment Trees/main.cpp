#include <iostream>
#include<fstream>
#define inf -1e7
#define nmax 100010
using namespace std;
ifstream f("arbint.in");
ofstream g("arbint.out");
int tree[4*nmax],v[nmax],a,b,i,n,m,x;
void constructTree(int poz,int ls,int ld)
{
    if(ls==ld)
    {
        tree[poz]=v[ls];
        return;
    }
    constructTree(2*poz,ls,(ls+ld)/2);
    constructTree(2*poz+1,(ls+ld)/2 + 1,ld);
    tree[poz]=max(tree[2*poz],tree[2*poz+1]);
}
int querry(int ls,int ld,int a,int b,int poz)
{
    if(a<=ls && b>=ld)
        return tree[poz];
    if(ld<a || ls>b)
        return 0;
    else
    {
        int mij=(ls+ld)/2;
        return max(querry(ls,mij,a,b,2*poz) , querry(mij+1,ld,a,b,2*poz+1));
    }
}
void update(int ls,int ld,int poz)
{
    if(ls>a || ld <a)
        return;
    if(ls==ld && ls==a)
    {
        tree[poz]=v[a];
        return;
    }
    int mij=(ls+ld)/2;
    update(ls,mij,2*poz);
    update(mij+1,ld,2*poz+1);
    tree[poz]=max(tree[2*poz],tree[2*poz+1]);
}
int main()
{
    f>>n>>m;
    for(i=1;i<=n;i++)
        f>>v[i];
    constructTree(1,1,n);
    for(i=1;i<=m;i++)
    {
        f>>x>>a>>b;
        if(!x)
            g<<querry(1,n,a,b,1)<<'\n';
        else
        {
            v[a]=b;
            update(1,n,1);
        }
    }
}
